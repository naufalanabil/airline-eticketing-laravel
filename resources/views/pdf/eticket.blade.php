<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Ticket {{ $booking->booking_code }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #222; }
        h1 { color: #14532d; }
        .section { margin-bottom: 18px; }
        .label { color: #666; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .qr { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>International E-Ticket</h1>

    <div class="section">
        <div class="label">Kode Booking</div>
        <strong>{{ $booking->booking_code }}</strong>
    </div>

    <div class="section">
        <div class="label">Penerbangan</div>
        <strong>{{ $booking->flight->airline->name }} - {{ $booking->flight->flight_number }}</strong>
    </div>

    <div class="section">
        <div class="label">Rute</div>
        {{ $booking->flight->originAirport->city }} ({{ $booking->flight->originAirport->code }})
        &rarr;
        {{ $booking->flight->destinationAirport->city }} ({{ $booking->flight->destinationAirport->code }})
    </div>

    <div class="section">
        <div class="label">Detail Perjalanan</div>
        <strong>Kelas:</strong> {{ $booking->seat_class }}<br>
        <strong>Bagasi:</strong> {{ $booking->baggage_weight }} kg<br>
        <strong>Meal:</strong> {{ $booking->has_meal ? 'Termasuk' : 'Tidak termasuk' }}<br>
        <strong>Asuransi:</strong> {{ $booking->has_insurance ? 'Termasuk' : 'Tidak termasuk' }}<br>
        <strong>Total add-ons:</strong> Rp {{ number_format($booking->addons_total, 0, ',', '.') }}
    </div>

    <div class="section">
        <div class="label">Penumpang</div>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Nomor Paspor</th>
                    <th>Kewarganegaraan</th>
                    <th>Kursi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($booking->passengers as $passenger)
                    <tr>
                        <td>{{ $passenger->full_name }}</td>
                        <td>{{ $passenger->passport_number }}</td>
                        <td>{{ $passenger->nationality }}</td>
                        <td>{{ $passenger->seat_number ?? 'Belum Dipilih' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="qr">
        <div>Scan untuk memverifikasi tiket</div>
        <img src="{{ $qrCodeBase64 }}" alt="QR Code Verifikasi" width="120" height="120">
    </div>
</body>
</html>
