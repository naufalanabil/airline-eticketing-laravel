<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi E-Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
<div class="container">
    <div class="card shadow-sm mx-auto" style="max-width: 760px;">
        <div class="card-header bg-primary text-white">
            <h1 class="h5 mb-0">Verifikasi E-Ticket</h1>
        </div>
        <div class="card-body">
            @if ($isValid)
                <div class="alert {{ $isPaid ? 'alert-success' : 'alert-warning' }}">
                    <strong>Tiket valid.</strong>
                    Pembayaran: <strong>{{ strtoupper($booking->status) }}</strong>
                </div>

                <dl class="row mb-4">
                    <dt class="col-sm-4">Kode Booking</dt>
                    <dd class="col-sm-8">{{ $booking->booking_code }}</dd>

                    @if ($booking->flight)
                        <dt class="col-sm-4">Penerbangan</dt>
                        <dd class="col-sm-8">
                            {{ $booking->flight->flight_number }}
                            @if ($booking->flight->airline)
                                - {{ $booking->flight->airline->name }}
                            @endif
                        </dd>
                        <dt class="col-sm-4">Rute</dt>
                        <dd class="col-sm-8">
                            {{ $booking->flight->originAirport?->city ?? '-' }}
                            ({{ $booking->flight->originAirport?->code ?? '-' }})
                            &rarr;
                            {{ $booking->flight->destinationAirport?->city ?? '-' }}
                            ({{ $booking->flight->destinationAirport?->code ?? '-' }})
                        </dd>
                    @endif
                </dl>

                <h2 class="h6">Daftar Penumpang</h2>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Nomor Paspor</th>
                                <th>Kursi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($booking->passengers as $passenger)
                                <tr>
                                    <td>{{ $passenger->full_name }}</td>
                                    <td>{{ $passenger->passport_number }}</td>
                                    <td>{{ $passenger->seat_number ?? 'Belum Dipilih' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data penumpang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-danger mb-0">
                    Tiket tidak valid atau kode booking tidak ditemukan.
                </div>
            @endif
        </div>
    </div>
</div>
</body>
</html>
