<?php

namespace App\Http\Controllers;

use App\Mail\EticketMail;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\PaymentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PaymentCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash('sha512', $request->order_id.$request->status_code.$request->gross_amount.$serverKey);

        if (! hash_equals($hashed, (string) $request->signature_key)) {
            return response()->json(['message' => 'Invalid signature.'], 403);
        }

        $bookingCode = $request->order_id;

        if (preg_match('/^(.*)-(\d{10})$/', $request->order_id, $matches)) {
            $bookingCode = $matches[1];
        }

        $booking = Booking::where('booking_code', $bookingCode)->first();

        if (! $booking) {
            return response()->json(['message' => 'Booking not found.'], 404);
        }

        PaymentLog::create([
            'booking_id' => $booking->id,
            'order_id' => $request->string('order_id')->toString(),
            'transaction_status' => $request->string('transaction_status')->toString(),
            'gross_amount' => $request->input('gross_amount'),
            'payload' => $request->all(),
        ]);

        $wasPaid = strtolower($booking->status) === 'paid';

        if (in_array($request->transaction_status, ['capture', 'settlement'], true)) {
            $booking->status = 'paid';

            if (! $wasPaid) {
                $booking->save();

                app(BookingController::class)->generatePdf($booking->id);
                $booking->refresh();

                if ($booking->user?->email) {
                    Mail::to($booking->user->email)->send(new EticketMail($booking));
                }
            }
        } elseif ($request->transaction_status === 'pending') {
            $booking->status = 'pending';
            $booking->save();
        } elseif (in_array($request->transaction_status, ['cancel', 'deny', 'expire'], true)) {
            DB::transaction(function () use ($booking): void {
                $lockedBooking = Booking::with('passengers')->lockForUpdate()->find($booking->id);

                if (! $lockedBooking || $lockedBooking->status !== 'pending') {
                    return;
                }

                Flight::whereKey($lockedBooking->flight_id)
                    ->lockForUpdate()
                    ->increment('available_seats', $lockedBooking->passengers->count());
                $lockedBooking->update(['status' => 'cancelled']);
            });
        }

        return response()->json(['status' => 'success']);
    }
}
