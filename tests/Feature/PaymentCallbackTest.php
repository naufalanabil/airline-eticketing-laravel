<?php

namespace Tests\Feature;

use App\Models\Airline;
use App\Models\Airport;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentCallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_midtrans_expire_callback_logs_status_and_releases_seat(): void
    {
        $user = User::factory()->create();
        $airline = Airline::create(['code' => 'GA', 'name' => 'Garuda Indonesia']);
        $origin = Airport::create(['code' => 'CGK', 'name' => 'Soekarno-Hatta', 'city' => 'Jakarta', 'country' => 'Indonesia']);
        $destination = Airport::create(['code' => 'SIN', 'name' => 'Changi', 'city' => 'Singapore', 'country' => 'Singapore']);
        $flight = Flight::create([
            'flight_number' => 'GA-002',
            'airline_id' => $airline->id,
            'origin_airport_id' => $origin->id,
            'destination_airport_id' => $destination->id,
            'departure_time' => now()->addDays(2),
            'arrival_time' => now()->addDays(2)->addHours(2),
            'price' => 1000000,
            'total_seats' => 20,
            'available_seats' => 19,
        ]);
        $booking = Booking::create([
            'booking_code' => 'BK-CALLBACK',
            'user_id' => $user->id,
            'flight_id' => $flight->id,
            'seat_class' => 'Economy',
            'total_amount' => 1000000,
            'status' => 'pending',
        ]);
        $booking->passengers()->create([
            'full_name' => 'Callback Passenger',
            'passport_number' => 'E12345678',
            'passport_expiry' => now()->addYear()->toDateString(),
            'nationality' => 'Indonesia',
            'seat_number' => '1A',
        ]);

        $orderId = $booking->booking_code.'-'.time();
        $grossAmount = '1000000.00';
        $payload = [
            'order_id' => $orderId,
            'status_code' => '200',
            'gross_amount' => $grossAmount,
            'transaction_status' => 'expire',
            'signature_key' => hash('sha512', $orderId.'200'.$grossAmount.config('services.midtrans.server_key')),
        ];

        $response = $this->postJson('/api/midtrans-callback', $payload);

        $response->assertOk();
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'cancelled']);
        $this->assertDatabaseHas('payment_logs', ['booking_id' => $booking->id, 'transaction_status' => 'expire']);
        $this->assertSame(20, $flight->fresh()->available_seats);
    }
}
