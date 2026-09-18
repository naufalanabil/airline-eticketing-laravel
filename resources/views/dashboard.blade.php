<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Riwayat Pemesanan Tiket Internasional') }}
            </h2>
            <a href="{{ route('booking.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                + Pesan Tiket Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('error'))
                    <div class="mb-6 rounded-lg bg-red-100 px-4 py-3 text-sm text-red-800" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-6 rounded-lg bg-emerald-100 px-4 py-3 text-sm text-emerald-800" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($bookings->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-500 mb-4">Anda belum memiliki riwayat pemesanan tiket.</p>
                        <a href="{{ route('booking.create') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                            Cari &amp; Pesan Tiket Sekarang
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto rounded-xl border border-gray-100 bg-white shadow-sm">
                        <table class="w-full border-collapse text-left">
                            <thead>
                                <tr class="border-b border-gray-100 bg-gray-50 text-[11px] font-bold uppercase tracking-wider text-gray-400">
                                    <th class="whitespace-nowrap px-6 py-4">Kode Booking</th>
                                    <th class="whitespace-nowrap px-6 py-4">Maskapai &amp; Rute</th>
                                    <th class="whitespace-nowrap px-6 py-4">Penumpang (Paspor)</th>
                                    <th class="whitespace-nowrap px-6 py-4">Total Biaya</th>
                                    <th class="whitespace-nowrap px-6 py-4">Status</th>
                                    <th class="whitespace-nowrap px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                                @forelse ($bookings as $booking)
                                    <tr class="transition hover:bg-gray-50/50">
                                        <td class="whitespace-nowrap px-6 py-4 font-mono font-bold text-blue-600">
                                            {{ $booking->booking_code }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2 font-bold text-gray-800">
                                                @if ($booking->flight?->airline)
                                                    <img src="{{ $booking->flight->airline->logoUrl() }}" onerror="this.onerror=null;this.src='{{ $booking->flight->airline->fallbackLogoUrl() }}';" alt="{{ $booking->flight->airline->name }}" width="28" height="28" class="rounded border bg-white p-1 object-contain">
                                                @endif
                                                <span>{{ $booking->flight?->airline?->name ?? '-' }} ({{ $booking->flight?->flight_number ?? '-' }})</span>
                                            </div>
                                            <div class="mt-0.5 whitespace-nowrap text-xs text-gray-400">
                                                {{ $booking->flight?->originAirport?->city ?? '-' }} ({{ $booking->flight?->originAirport?->code ?? '-' }})
                                                &rarr;
                                                {{ $booking->flight?->destinationAirport?->city ?? '-' }} ({{ $booking->flight?->destinationAirport?->code ?? '-' }})
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @forelse ($booking->passengers as $passenger)
                                                <div class="font-semibold text-gray-800">{{ $passenger->full_name }}</div>
                                                <div class="text-xs text-gray-400">Passport: {{ $passenger->passport_number }}</div>
                                                <div class="mt-1 inline-block rounded border border-blue-100 bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-600">
                                                    Kursi: {{ $passenger->seat_number ?? 'Belum Dipilih' }}
                                                </div>
                                            @empty
                                                <span class="text-gray-400">-</span>
                                            @endforelse
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 font-bold text-gray-800">
                                            Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            @if (in_array(strtolower($booking->status), ['paid', 'success', 'settlement'], true))
                                                <span class="inline-block rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-700">PAID (LUNAS)</span>
                                            @elseif (strtolower($booking->status) === 'pending')
                                                <span class="inline-block rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-700">PENDING</span>
                                            @else
                                                <span class="inline-block rounded-full bg-red-100 px-2.5 py-1 text-xs font-bold text-red-700">CANCELLED</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('booking.show', $booking->id) }}" class="rounded-lg bg-gray-100 px-3 py-1.5 text-xs font-bold text-gray-700 transition hover:bg-gray-200" title="Lihat Detail">Detail</a>
                                                <a href="{{ route('booking.edit', $booking->id) }}" class="rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-amber-600" title="Edit Data">Edit</a>
                                                @if (in_array(strtolower($booking->status), ['paid', 'success', 'settlement'], true))
                                                    <a href="{{ route('booking.ticket', $booking->id) }}" target="_blank" class="flex items-center gap-1 rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-bold text-white shadow-sm transition hover:bg-blue-700">Cetak Tiket</a>
                                                    <a href="{{ route('booking.eticket', $booking->id) }}" class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-indigo-700">PDF</a>
                                                    @php
                                                        $shareText = rawurlencode("E-ticket {$booking->booking_code}: ".route('booking.verify', $booking->booking_code));
                                                    @endphp
                                                    <a href="https://wa.me/?text={{ $shareText }}" target="_blank" rel="noopener" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-emerald-700">WhatsApp</a>
                                                @else
                                                    <a href="{{ route('booking.checkout', $booking->id) }}" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-emerald-700">Bayar Sekarang</a>
                                                @endif
                                                <form action="{{ route('booking.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pemesanan ini?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded-lg bg-red-500 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-red-600" title="Hapus Pemesanan">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-400">Belum ada riwayat pemesanan tiket.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
