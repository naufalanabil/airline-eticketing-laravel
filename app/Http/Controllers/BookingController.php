<?php

namespace App\Http\Controllers;

use App\Models\Airline;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\Passenger;
use App\Models\Voucher;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BookingController extends Controller
{
    public function verifyTicket(string $code)
    {
        $booking = Booking::with(['flight.airline', 'flight.originAirport', 'flight.destinationAirport', 'passengers'])
            ->where('booking_code', $code)
            ->firstOrFail();

        return view('booking.verify', compact('booking'));
    }

    public function create(Request $request)
    {
        $flightQuery = Flight::with(['airline', 'originAirport', 'destinationAirport'])
            ->where('available_seats', '>', 0);

        $origin = trim($request->string('origin')->toString());
        $destination = trim($request->string('destination')->toString());
        $airlineCodes = collect($request->input('airline', []))
            ->filter(fn ($code) => is_string($code) && preg_match('/^[A-Z0-9]{2,10}$/', $code))
            ->values();
        $transitFilters = collect($request->input('transit', []))
            ->filter(fn ($filter) => in_array($filter, ['direct', '1_transit'], true))
            ->values();
        $maxPrice = $request->integer('max_price');

        if ($origin !== '') {
            $flightQuery->whereHas('originAirport', function ($query) use ($origin): void {
                $query->where('city', 'like', "%{$origin}%")
                    ->orWhere('code', 'like', "%{$origin}%");
            });
        }

        if ($destination !== '') {
            $flightQuery->whereHas('destinationAirport', function ($query) use ($destination): void {
                $query->where('city', 'like', "%{$destination}%")
                    ->orWhere('code', 'like', "%{$destination}%");
            });
        }

        if ($request->filled('date')) {
            $flightQuery->whereDate('departure_time', $request->date('date'));
        }

        if ($airlineCodes->isNotEmpty()) {
            $flightQuery->whereHas('airline', fn ($query) => $query->whereIn('code', $airlineCodes));
        }

        if ($maxPrice > 0) {
            $flightQuery->where('price', '<=', $maxPrice);
        }

        if ($transitFilters->isNotEmpty()) {
            $flightQuery->where(function ($query) use ($transitFilters): void {
                if ($transitFilters->contains('direct')) {
                    $query->where('stops', 0);
                }

                if ($transitFilters->contains('1_transit')) {
                    $method = $transitFilters->contains('direct') ? 'orWhere' : 'where';
                    $query->{$method}('stops', 1);
                }
            });
        }

        $flights = $flightQuery->orderBy('price')->get();
        $airlines = Airline::query()->orderBy('name')->get();

        $bookedSeatsByFlight = Passenger::query()
            ->join('bookings', 'bookings.id', '=', 'passengers.booking_id')
            ->whereIn('bookings.flight_id', $flights->pluck('id'))
            ->whereNotNull('passengers.seat_number')
            ->get(['bookings.flight_id', 'passengers.seat_number'])
            ->groupBy('flight_id')
            ->map(fn ($passengers) => $passengers->pluck('seat_number')->values()->all())
            ->all();

        return view('booking.create', compact('flights', 'bookedSeatsByFlight', 'airlines'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'flight_id' => ['required', 'integer', 'exists:flights,id'],
            'passengers' => ['required', 'array', 'min:1'],
            'passengers.*.full_name' => ['required', 'string', 'max:255'],
            'passengers.*.passport_number' => ['required', 'string', 'max:50'],
            'passengers.*.passport_expiry' => ['required', 'date', 'after:today'],
            'passengers.*.nationality' => ['required', 'string', 'max:50'],
            'passengers.*.seat_number' => ['required', 'string', 'regex:/^[1-9][0-9]?[A-D]$/', 'distinct'],
            'seat_class' => ['required', 'string', Rule::in(['Economy', 'Business', 'First Class'])],
            'extra_baggage' => ['nullable', 'boolean'],
            'has_meal' => ['nullable', 'boolean'],
            'has_insurance' => ['nullable', 'boolean'],
            'voucher_code' => ['nullable', 'string', 'max:50'],
        ]);

        $booking = DB::transaction(function () use ($validated) {
            $flight = Flight::whereKey($validated['flight_id'])
                ->lockForUpdate()
                ->firstOrFail();
            $passengerCount = count($validated['passengers']);

            if ($flight->available_seats < $passengerCount) {
                abort(422, 'Kursi yang tersedia tidak mencukupi.');
            }

            $passengers = array_map(function (array $passenger) use ($flight): array {
                $seatNumber = strtoupper($passenger['seat_number']);
                $row = (int) substr($seatNumber, 0, -1);
                $column = ord(substr($seatNumber, -1)) - ord('A') + 1;

                if (($row - 1) * 4 + $column > $flight->total_seats) {
                    abort(422, "Kursi {$seatNumber} tidak tersedia pada penerbangan ini.");
                }

                $passenger['seat_number'] = $seatNumber;

                return $passenger;
            }, $validated['passengers']);

            $seatNumbers = array_column($passengers, 'seat_number');
            $seatIsTaken = Passenger::whereHas('booking', function ($query) use ($flight) {
                $query->where('flight_id', $flight->id);
            })->whereIn('seat_number', $seatNumbers)->exists();

            if ($seatIsTaken) {
                abort(422, 'Salah satu kursi sudah dipilih penumpang lain. Silakan pilih kursi lain.');
            }

            $hasExtraBaggage = (bool) ($validated['extra_baggage'] ?? false);
            $hasMeal = (bool) ($validated['has_meal'] ?? false);
            $hasInsurance = (bool) ($validated['has_insurance'] ?? false);
            $addonsTotal = ($hasExtraBaggage ? 150000 : 0)
                + ($hasMeal ? 75000 : 0)
                + ($hasInsurance ? 50000 : 0);
            $baseAmount = ($flight->price * $passengerCount) + $addonsTotal;
            $voucher = null;
            $discountAmount = 0;

            if (filled($validated['voucher_code'] ?? null)) {
                $voucherCode = strtoupper(trim($validated['voucher_code']));
                $voucher = Voucher::where('code', $voucherCode)->lockForUpdate()->first();

                if (! $voucher || $voucher->valid_until->isPast()) {
                    throw ValidationException::withMessages(['voucher_code' => 'Kode voucher tidak valid atau sudah kedaluwarsa.']);
                }

                if ($voucher->bookings()->whereIn('status', ['pending', 'paid'])->count() >= $voucher->max_uses) {
                    throw ValidationException::withMessages(['voucher_code' => 'Kuota voucher sudah habis.']);
                }

                $discountAmount = $voucher->type === 'percent'
                    ? min($baseAmount, $baseAmount * ((float) $voucher->amount / 100))
                    : min($baseAmount, (float) $voucher->amount);
            }

            $booking = Booking::create([
                'booking_code' => 'BK-'.strtoupper(Str::random(10)),
                'user_id' => auth()->id(),
                'flight_id' => $flight->id,
                'seat_class' => $validated['seat_class'],
                'baggage_weight' => $hasExtraBaggage ? 30 : 20,
                'has_meal' => $hasMeal,
                'has_insurance' => $hasInsurance,
                'total_amount' => max(0, $baseAmount - $discountAmount),
                'addons_total' => $addonsTotal,
                'voucher_id' => $voucher?->id,
                'discount_amount' => $discountAmount,
                'payment_expires_at' => now()->addMinutes(15),
                'status' => 'pending',
            ]);

            $booking->passengers()->createMany($passengers);
            $flight->decrement('available_seats', $passengerCount);

            return $booking;
        });

        return redirect()->route('booking.checkout', $booking->id);
    }

    public function checkout($id)
    {
        $booking = Booking::with(['flight.airline', 'flight.originAirport', 'flight.destinationAirport', 'passengers'])->findOrFail($id);

        if ($this->expireBookingIfNeeded($booking)) {
            return redirect()->route('dashboard')->with('error', 'Batas waktu pembayaran telah berakhir. Kursi dikembalikan ke penerbangan.');
        }

        if ($booking->status === 'pending' && ! $booking->payment_expires_at) {
            $booking->payment_expires_at = now()->addMinutes(15);
            $booking->save();
        }

        if (! $booking->snap_token) {
            try {
                $serverKey = config('services.midtrans.server_key');
                $clientKey = config('services.midtrans.client_key');

                if (blank($serverKey) || str_contains($serverKey, 'xxxxxxxx') || blank($clientKey) || str_contains($clientKey, 'xxxxxxxx')) {
                    throw new \RuntimeException('Midtrans belum dikonfigurasi dengan key Sandbox yang valid.');
                }

                Config::$serverKey = $serverKey;
                Config::$isProduction = config('services.midtrans.is_production');
                Config::$isSanitized = config('services.midtrans.is_sanitized');
                Config::$is3ds = config('services.midtrans.is_3ds');

                $params = [
                    'transaction_details' => [
                        'order_id' => $booking->booking_code.'-'.time(),
                        'gross_amount' => (int) $booking->total_amount,
                    ],
                    'customer_details' => [
                        'first_name' => $booking->passengers->first()->full_name ?? 'Passenger',
                        'email' => $booking->user?->email ?? auth()->user()?->email,
                    ],
                    'callbacks' => [
                        'finish' => route('payment.finish'),
                        'unfinish' => route('payment.finish'),
                        'error' => route('payment.finish'),
                    ],
                ];

                $booking->snap_token = Snap::getSnapToken($params);
                $booking->save();
            } catch (\Throwable $exception) {
                Log::error('Midtrans Snap token creation failed.', [
                    'booking_id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'exception' => $exception,
                ]);

                return back()->with('error', 'Gagal terhubung ke Midtrans. Periksa konfigurasi dan koneksi internet.');
            }
        }

        return view('booking.checkout', compact('booking'));
    }

    private function expireBookingIfNeeded(Booking $booking): bool
    {
        if ($booking->status !== 'pending' || ! $booking->payment_expires_at || $booking->payment_expires_at->isFuture()) {
            return false;
        }

        DB::transaction(function () use ($booking): void {
            $lockedBooking = Booking::with('passengers')->lockForUpdate()->find($booking->id);

            if (! $lockedBooking || $lockedBooking->status !== 'pending' || $lockedBooking->payment_expires_at?->isFuture()) {
                return;
            }

            Flight::whereKey($lockedBooking->flight_id)
                ->lockForUpdate()
                ->increment('available_seats', $lockedBooking->passengers->count());
            $lockedBooking->update(['status' => 'cancelled']);
        });

        return true;
    }

    public function paymentFinish(Request $request)
    {
        return $this->paymentSuccess($request);
    }

    public function paymentSuccess(Request $request)
    {
        $orderId = $request->string('order_id')->toString();

        if (blank($orderId)) {
            return redirect()->route('dashboard')->with('error', 'Pembayaran belum dapat dikonfirmasi.');
        }

        try {
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.is_production');

            $transaction = Transaction::status($orderId);
            $transactionStatus = strtolower((string) data_get($transaction, 'transaction_status', ''));

            if (in_array($transactionStatus, ['capture', 'settlement'], true)) {
                $bookingCode = preg_replace('/-\d{10}$/', '', $orderId);
                $booking = Booking::where('booking_code', $bookingCode)
                    ->where('user_id', auth()->id())
                    ->first();

                if ($booking && $booking->status !== 'paid') {
                    $booking->update(['status' => 'paid']);
                }

                return redirect()->route('dashboard')->with('success', 'Pembayaran berhasil dikonfirmasi.');
            }
        } catch (\Throwable $exception) {
            Log::warning('Midtrans payment return could not verify transaction.', [
                'order_id' => $orderId,
                'exception' => $exception,
            ]);
        }

        return redirect()->route('dashboard')->with('error', 'Pembayaran masih menunggu konfirmasi Midtrans.');
    }

    public function downloadTicket($id)
    {
        $booking = Booking::with(['flight.airline', 'flight.originAirport', 'flight.destinationAirport', 'passengers'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        abort_unless(in_array(strtolower($booking->status), ['paid', 'success', 'settlement'], true), 403);

        return view('booking.ticket', compact('booking'));
    }

    public function generatePdf($bookingId)
    {
        $bookingQuery = Booking::with(['flight.airline', 'flight.originAirport', 'flight.destinationAirport', 'passengers'])
            ->whereKey($bookingId);

        if (auth()->check()) {
            $bookingQuery->where('user_id', auth()->id());
        }

        $booking = $bookingQuery->firstOrFail();

        abort_unless(in_array(strtolower($booking->status), ['paid', 'success', 'settlement'], true), 403);

        $verifyUrl = route('booking.verify', $booking->booking_code);
        $qrCode = QrCode::format('svg')->size(120)->generate($verifyUrl);
        $qrCodeBase64 = 'data:image/svg+xml;base64,'.base64_encode($qrCode);

        $pdf = Pdf::loadView('pdf.eticket', compact('booking', 'qrCodeBase64'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isFontSubsettingEnabled' => false,
                'isHtml5ParserEnabled' => true,
            ]);

        $fileName = 'eticket_'.$booking->booking_code.'.pdf';
        $filePath = 'tickets/'.$fileName;
        Storage::disk('public')->put($filePath, $pdf->output());

        $booking->pdf_path = $filePath;
        $booking->save();

        return $pdf->download($fileName);
    }

    public function show($id)
    {
        $booking = Booking::with(['flight.airline', 'flight.originAirport', 'flight.destinationAirport', 'passengers', 'paymentLogs'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('booking.show', compact('booking'));
    }

    public function edit($id)
    {
        $booking = Booking::with('passengers')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('booking.edit', compact('booking'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'passport_number' => ['required', 'string', 'max:50'],
            'passport_expiry' => ['required', 'date', 'after:today'],
            'nationality' => ['required', 'string', 'max:50'],
        ]);

        $booking = Booking::with('passengers')
            ->where('user_id', auth()->id())
            ->findOrFail($id);
        $passenger = $booking->passengers->firstOrFail();
        $passenger->update($validated);

        return redirect()->route('dashboard')->with('success', 'Data penumpang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $booking = Booking::with('passengers')
                ->where('user_id', auth()->id())
                ->lockForUpdate()
                ->findOrFail($id);
            $flight = Flight::whereKey($booking->flight_id)->lockForUpdate()->firstOrFail();

            if ($booking->status !== 'cancelled') {
                $flight->increment('available_seats', $booking->passengers->count());
            }

            if ($booking->pdf_path) {
                Storage::disk('public')->delete($booking->pdf_path);
            }

            $booking->delete();
        });

        return redirect()->route('dashboard')->with('success', 'Pemesanan berhasil dihapus.');
    }

    public function dashboard()
    {
        $bookings = Booking::with(['flight.airline', 'flight.originAirport', 'flight.destinationAirport', 'passengers'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('dashboard', compact('bookings'));
    }
}
