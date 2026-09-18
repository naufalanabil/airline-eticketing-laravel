<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - {{ $booking->booking_code }}</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Arial, Helvetica, sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            padding: 24px;
            background: #f1f5f9;
            color: #1e293b;
        }

        .actions,
        .ticket-card {
            width: min(100%, 820px);
            margin-right: auto;
            margin-left: auto;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }

        .back-link {
            color: #2563eb;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
        }

        .print-button {
            padding: 10px 16px;
            border: 0;
            border-radius: 8px;
            background: #2563eb;
            color: #ffffff;
            cursor: pointer;
            font-weight: 700;
        }

        .ticket-card {
            overflow: hidden;
            border: 2px solid #2563eb;
            border-radius: 14px;
            background: #ffffff;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.12);
        }

        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 24px;
            padding: 24px 28px;
            background: #2563eb;
            color: #ffffff;
        }

        .brand {
            margin: 0;
            font-size: 26px;
            letter-spacing: 0.04em;
        }

        .subtitle,
        .code-label {
            margin: 5px 0 0;
            color: #dbeafe;
            font-size: 11px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .booking-code {
            text-align: right;
        }

        .code {
            margin: 5px 0 0;
            color: #ffffff;
            font-family: monospace;
            font-size: 20px;
            font-weight: 700;
        }

        .ticket-content {
            padding: 28px;
        }

        .route {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 20px;
            align-items: center;
            padding-bottom: 24px;
            border-bottom: 1px solid #e2e8f0;
        }

        .route-point:last-child {
            text-align: right;
        }

        .airport {
            margin: 0;
            color: #0f172a;
            font-size: 30px;
            font-weight: 800;
        }

        .city,
        .time {
            margin: 5px 0 0;
            color: #64748b;
            font-size: 13px;
        }

        .arrow {
            color: #2563eb;
            font-size: 28px;
        }

        .details {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 24px 0;
        }

        .label {
            margin: 0 0 6px;
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .value {
            margin: 0;
            color: #0f172a;
            font-size: 15px;
            font-weight: 700;
        }

        .passenger-title {
            margin: 0 0 10px;
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .passenger-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .passenger-table th,
        .passenger-table td {
            padding: 11px 8px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }

        .passenger-table th {
            color: #64748b;
            font-size: 11px;
            text-transform: uppercase;
        }

        .ticket-footer {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 28px;
            border-top: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #64748b;
            font-size: 12px;
        }

        @media (max-width: 640px) {
            body {
                padding: 12px;
            }

            .actions,
            .ticket-header,
            .ticket-footer {
                align-items: flex-start;
                flex-direction: column;
            }

            .booking-code,
            .route-point:last-child {
                text-align: left;
            }

            .ticket-content,
            .ticket-header {
                padding: 20px;
            }

            .details {
                grid-template-columns: 1fr 1fr;
            }

            .passenger-table {
                font-size: 12px;
            }
        }

        @media print {
            body {
                padding: 0;
                background: #ffffff;
            }

            .no-print {
                display: none !important;
            }

            .ticket-card {
                width: 100%;
                border: 2px solid #2563eb;
                border-radius: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="actions no-print">
        <a class="back-link" href="{{ route('dashboard') }}">&larr; Kembali ke Dashboard</a>
        <button class="print-button" type="button" onclick="window.print()">Cetak / Simpan ke PDF</button>
        <a class="print-button" href="{{ route('booking.eticket', $booking->id) }}">Download PDF</a>
        @php
            $shareText = rawurlencode("E-ticket {$booking->booking_code}: {$booking->flight->originAirport->city} ke {$booking->flight->destinationAirport->city}. Verifikasi: ".route('booking.verify', $booking->booking_code));
        @endphp
        <a class="print-button" href="https://wa.me/?text={{ $shareText }}" target="_blank" rel="noopener">Share WhatsApp</a>
    </div>

    <main class="ticket-card">
        <header class="ticket-header">
            <div>
                <h1 class="brand">AirTicket Intl</h1>
                <p class="subtitle">Electronic Boarding Pass</p>
            </div>
            <div class="booking-code">
                <p class="code-label">Kode Booking</p>
                <p class="code">{{ $booking->booking_code }}</p>
            </div>
        </header>

        <section class="ticket-content">
            <div class="route">
                <div class="route-point">
                    <p class="airport">{{ $booking->flight?->originAirport?->code ?? '-' }}</p>
                    <p class="city">{{ $booking->flight?->originAirport?->city ?? '-' }}</p>
                    <p class="time">{{ $booking->flight?->departure_time?->format('d M Y, H:i') ?? '-' }}</p>
                </div>
                <div class="arrow" aria-hidden="true">&rarr;</div>
                <div class="route-point">
                    <p class="airport">{{ $booking->flight?->destinationAirport?->code ?? '-' }}</p>
                    <p class="city">{{ $booking->flight?->destinationAirport?->city ?? '-' }}</p>
                    <p class="time">{{ $booking->flight?->arrival_time?->format('d M Y, H:i') ?? '-' }}</p>
                </div>
            </div>

            <div class="details">
                <div>
                    <p class="label">Maskapai</p>
                    <p class="value">{{ $booking->flight?->airline?->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="label">Nomor Penerbangan</p>
                    <p class="value">{{ $booking->flight?->flight_number ?? '-' }}</p>
                </div>
                <div>
                    <p class="label">Status Pembayaran</p>
                    <p class="value">{{ strtoupper($booking->status) }}</p>
                </div>
            </div>

            <div class="details">
                <div>
                    <p class="label">Kelas</p>
                    <p class="value">{{ $booking->seat_class }}</p>
                </div>
                <div>
                    <p class="label">Bagasi</p>
                    <p class="value">{{ $booking->baggage_weight }} kg</p>
                </div>
                <div>
                    <p class="label">Add-ons</p>
                    <p class="value">Rp {{ number_format($booking->addons_total, 0, ',', '.') }}</p>
                </div>
            </div>

            <p class="passenger-title">Layanan Tambahan</p>
            <p>{{ $booking->has_meal ? 'In-flight meal' : 'Tanpa meal' }} · {{ $booking->has_insurance ? 'Asuransi perjalanan' : 'Tanpa asuransi' }}</p>

            <p class="passenger-title">Daftar Penumpang</p>
            <table class="passenger-table">
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
        </section>

        <footer class="ticket-footer">
            <span>Status: <strong>{{ strtoupper($booking->status) }}</strong></span>
            <span>Tunjukkan e-ticket dan paspor saat check-in.</span>
        </footer>
    </main>
</body>
</html>
