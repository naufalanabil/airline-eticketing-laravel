<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>International Flight Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow-sm max-w-lg mx-auto">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Payment Checkout - {{ $booking->booking_code }}</h5>
        </div>
        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if($booking->status === 'pending' && $booking->payment_expires_at)
                <div class="alert alert-warning d-flex justify-content-between align-items-center" role="status">
                    <span><strong>Batas pembayaran</strong><br><small>Selesaikan pembayaran sebelum waktu berakhir.</small></span>
                    <strong id="countdown" class="fs-4 font-monospace" data-expires-at="{{ $booking->payment_expires_at->toIso8601String() }}">--:--</strong>
                </div>
            @endif

            <div class="d-flex align-items-center gap-2 mb-3">
                <img src="{{ $booking->flight->airline->logoUrl() }}" onerror="this.onerror=null;this.src='{{ $booking->flight->airline->fallbackLogoUrl() }}';" alt="{{ $booking->flight->airline->name }}" width="42" height="42" class="rounded border bg-white p-1 object-contain">
                <h6 class="mb-0">Flight Details</h6>
            </div>
            <p class="mb-1"><strong>Airline:</strong> {{ $booking->flight->airline->name }} ({{ $booking->flight->flight_number }})</p>
            <p class="mb-1"><strong>Route:</strong> {{ $booking->flight->originAirport->city }} ({{ $booking->flight->originAirport->code }}) &rarr; {{ $booking->flight->destinationAirport->city }} ({{ $booking->flight->destinationAirport->code }})</p>
            <p class="mb-1"><strong>Class:</strong> {{ $booking->seat_class }}</p>
            <p class="mb-1"><strong>Baggage:</strong> {{ $booking->baggage_weight }} kg</p>
            <p class="mb-1"><strong>Add-ons:</strong> Rp {{ number_format($booking->addons_total, 0, ',', '.') }}</p>
            @if($booking->voucher)
                <p class="mb-1 text-success"><strong>Voucher {{ $booking->voucher->code }}:</strong> - Rp {{ number_format($booking->discount_amount, 0, ',', '.') }}</p>
            @endif
            <p class="mb-3"><strong>Total Amount:</strong> Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</p>

            <hr>
            <h6>Passengers</h6>
            <ul class="list-group mb-4">
                @foreach($booking->passengers as $p)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $p->full_name }}</strong><br>
                            <small class="text-muted">Passport: {{ $p->passport_number }} (Exp: {{ $p->passport_expiry }})</small><br>
                            <small class="text-muted">Seat: {{ $p->seat_number ?? 'Not selected' }}</small>
                        </div>
                        <span class="badge bg-secondary">{{ $p->nationality }}</span>
                    </li>
                @endforeach
            </ul>

            <div class="mb-4 small text-muted">
                <strong>Included services:</strong>
                {{ $booking->has_meal ? 'Meal' : 'No meal' }} ·
                {{ $booking->has_insurance ? 'Travel insurance' : 'No travel insurance' }}
            </div>

            @if($booking->status == 'pending')
                <button id="pay-button" class="btn btn-success w-100">Pay Now via Midtrans</button>
            @elseif(in_array(strtolower($booking->status), ['paid', 'success', 'settlement'], true))
                <div class="alert alert-success text-center">Payment Successful!</div>
                <a href="{{ route('booking.eticket', $booking->id) }}" class="btn btn-primary w-100">Download International E-Ticket (PDF)</a>
            @else
                <div class="alert alert-danger text-center">Booking Cancelled</div>
            @endif
        </div>
    </div>
</div>

<script type="text/javascript">
    const countdown = document.getElementById('countdown');
    if (countdown) {
        const expiresAt = new Date(countdown.dataset.expiresAt).getTime();
        const updateCountdown = () => {
            const remaining = Math.max(0, expiresAt - Date.now());
            const totalSeconds = Math.floor(remaining / 1000);
            const minutes = Math.floor(totalSeconds / 60).toString().padStart(2, '0');
            const seconds = (totalSeconds % 60).toString().padStart(2, '0');
            countdown.textContent = `${minutes}:${seconds}`;

            if (remaining <= 0) {
                window.location.href = @json(route('dashboard'));
            }
        };
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }

    var payButton = document.getElementById('pay-button');
    if (payButton) {
        payButton.addEventListener('click', function () {
            window.snap.pay('{{ $booking->snap_token }}', {
                onSuccess: function(result){ window.location.reload(); },
                onPending: function(result){ window.location.reload(); },
                onError: function(result){ alert("Payment failed!"); }
            });
        });
    }
</script>
</body>
</html>