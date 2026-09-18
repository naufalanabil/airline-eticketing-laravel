<!DOCTYPE html>
<html>
<head>
    <title>E-Ticket Issued</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Pembayaran Berhasil!</h2>
    <p>Halo, terima kasih telah melakukan pemesanan tiket penerbangan internasional.</p>
    <p>Berikut adalah rincian pemesanan Anda:</p>
    <ul>
        <li><strong>Kode Booking:</strong> {{ $booking->booking_code }}</li>
        <li><strong>Penerbangan:</strong> {{ $booking->flight->airline->name }} ({{ $booking->flight->flight_number }})</li>
        <li><strong>Rute:</strong> {{ $booking->flight->originAirport->city }} &rarr; {{ $booking->flight->destinationAirport->city }}</li>
    </ul>
    <p>E-Ticket PDF Anda telah terlampir pada email ini. Silakan cetak atau tunjukkan file tersebut beserta <strong>Paspor Asli</strong> saat di konter check-in bandara.</p>
    <br>
    <p>Selamat menikmati perjalanan Anda!</p>
</body>
</html>
