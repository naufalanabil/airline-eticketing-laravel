<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi E-Ticket International</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
<div class="container" style="max-width: 640px;">
    <div class="card shadow border-0">
        <div class="card-body text-center p-4">
            @if (in_array(strtolower($booking->status), ['paid', 'success', 'settlement'], true))
                <div class="badge bg-success fs-6 mb-3 px-3 py-2">VALID E-TICKET</div>
            @else
                <div class="badge bg-danger fs-6 mb-3 px-3 py-2">INVALID / UNPAID</div>
            @endif

            <h4 class="fw-bold">{{ $booking->booking_code }}</h4>
            <p class="text-muted mb-4">
                {{ $booking->flight->airline->name }} - {{ $booking->flight->flight_number }}
            </p>

            <div class="text-start bg-light p-3 rounded mb-3">
                <small class="text-secondary d-block">RUTE FLIGHT</small>
                <strong>
                    {{ $booking->flight->originAirport->city }}
                    ({{ $booking->flight->originAirport->code }})
                    &rarr;
                    {{ $booking->flight->destinationAirport->city }}
                    ({{ $booking->flight->destinationAirport->code }})
                </strong>
                <hr class="my-2">
                <small class="text-secondary d-block">JADWAL KEBERANGKATAN</small>
                <strong>
                    {{ \Carbon\Carbon::parse($booking->flight->departure_time)->format('d M Y, H:i') }} LT
                </strong>
            </div>

            <div class="text-start">
                <small class="text-secondary d-block mb-2">DAFTAR PENUMPANG &amp; PASPOR</small>
                <ul class="list-group">
                    @foreach ($booking->passengers as $passenger)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ strtoupper($passenger->full_name) }}</strong><br>
                                <small class="text-muted">Passport: {{ $passenger->passport_number }}</small><br>
                                <small class="text-muted">Kursi: {{ $passenger->seat_number ?? 'Belum Dipilih' }}</small>
                            </div>
                            <span class="badge bg-info text-dark">{{ $passenger->nationality }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
</body>
</html>
