<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Pemesanan</h2>
            <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 underline hover:text-gray-900">Kembali ke Dashboard</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm">
                <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Kode Booking</p>
                        <h3 class="text-2xl font-bold text-blue-600">{{ $booking->booking_code }}</h3>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-bold {{ in_array(strtolower($booking->status), ['paid', 'success', 'settlement'], true) ? 'bg-emerald-100 text-emerald-800' : ($booking->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                        {{ strtoupper($booking->status) }}
                    </span>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="rounded-lg bg-gray-50 p-4">
                        <h4 class="mb-3 font-semibold text-gray-900">Penerbangan</h4>
                        <dl class="space-y-2 text-sm">
                            <div><dt class="text-gray-500">Maskapai</dt><dd>{{ $booking->flight?->airline?->name ?? '-' }} ({{ $booking->flight?->flight_number ?? '-' }})</dd></div>
                            <div><dt class="text-gray-500">Rute</dt><dd>{{ $booking->flight?->originAirport?->city ?? '-' }} &rarr; {{ $booking->flight?->destinationAirport?->city ?? '-' }}</dd></div>
                            <div><dt class="text-gray-500">Keberangkatan</dt><dd>{{ $booking->flight?->departure_time?->format('d M Y, H:i') ?? '-' }}</dd></div>
                            <div><dt class="text-gray-500">Kelas</dt><dd>{{ $booking->seat_class }}</dd></div>
                            <div><dt class="text-gray-500">Bagasi</dt><dd>{{ $booking->baggage_weight }} kg</dd></div>
                            <div><dt class="text-gray-500">Add-ons</dt><dd>Rp {{ number_format($booking->addons_total, 0, ',', '.') }}</dd></div>
                            <div><dt class="text-gray-500">Total</dt><dd class="font-semibold">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</dd></div>
                        </dl>
                    </div>

                    <div>
                        <h4 class="mb-3 font-semibold text-gray-900">Penumpang</h4>
                        <div class="space-y-3">
                            @forelse ($booking->passengers as $passenger)
                                <div class="rounded-lg border border-gray-200 p-4 text-sm">
                                    <p class="font-semibold text-gray-900">{{ $passenger->full_name }}</p>
                                    <p class="text-gray-600">Paspor: {{ $passenger->passport_number }}</p>
                                    <p class="text-gray-600">Berlaku hingga: {{ $passenger->passport_expiry }}</p>
                                    <p class="text-gray-600">Kewarganegaraan: {{ $passenger->nationality }}</p>
                                    <p class="font-semibold text-blue-600">Kursi: {{ $passenger->seat_number ?? 'Belum Dipilih' }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada data penumpang.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('booking.edit', $booking->id) }}" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-600">Edit Penumpang</a>
                    @if ($booking->status === 'pending')
                        <a href="{{ route('booking.checkout', $booking->id) }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Bayar Sekarang</a>
                    @elseif (in_array(strtolower($booking->status), ['paid', 'success', 'settlement'], true))
                        <a href="{{ route('booking.ticket', $booking->id) }}" target="_blank" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Cetak Tiket</a>
                        <a href="{{ route('booking.eticket', $booking->id) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Download E-Ticket</a>
                    @endif
                </div>

                @if ($booking->paymentLogs->isNotEmpty())
                    <div class="mt-8 border-t border-gray-100 pt-6">
                        <h4 class="mb-3 font-semibold text-gray-900">Riwayat Status Pembayaran</h4>
                        <div class="space-y-2 text-sm">
                            @foreach ($booking->paymentLogs->sortByDesc('created_at') as $paymentLog)
                                <div class="flex flex-wrap justify-between gap-2 rounded-lg bg-gray-50 px-3 py-2">
                                    <span class="font-semibold uppercase">{{ $paymentLog->transaction_status }}</span>
                                    <span class="text-gray-500">{{ $paymentLog->created_at->format('d M Y, H:i') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
