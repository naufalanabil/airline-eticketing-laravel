<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your E-Ticket</title>
</head>
<body>
    <p>Hello {{ $booking->user?->name ?? 'Passenger' }},</p>
    <p>Your payment for booking <strong>{{ $booking->booking_code }}</strong> was successful.</p>
    <p>Your e-ticket is attached to this email.</p>
</body>
</html>
