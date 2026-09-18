<?php

namespace Tests\Feature;

use App\Models\Airline;
use App\Models\Airport;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingDetailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_persists_class_addons_and_updated_total(): void
    {
        $user = User::factory()->create();
        $flight = $this->createFlight();
        Voucher::create([
            'code' => 'TIKETMURAH',
            'type' => 'fixed',
            'amount' => 100000,
            'max_uses' => 10,
            'valid_until' => now()->addMonth()->toDateString(),
        ]);

        $response = $this->actingAs($user)->post(route('booking.store'), [
            'flight_id' => $flight->id,
            'seat_class' => 'Business',
            'extra_baggage' => '1',
            'has_meal' => '1',
            'has_insurance' => '1',
            'voucher_code' => 'tiketmurah',
            'passengers' => [[
                'full_name' => 'Test Passenger',
                'passport_number' => 'A12345678',
                'passport_expiry' => now()->addYear()->toDateString(),
                'nationality' => 'Indonesia',
                'seat_number' => '1A',
            ]],
        ]);

        $booking = Booking::firstOrFail();

        $response->assertRedirect(route('booking.checkout', $booking->id, absolute: false));
        $this->assertSame('Business', $booking->seat_class);
        $this->assertSame(30, $booking->baggage_weight);
        $this->assertTrue($booking->has_meal);
        $this->assertTrue($booking->has_insurance);
        $this->assertSame('275000.00', $booking->addons_total);
        $this->assertSame('100000.00', $booking->discount_amount);
        $this->assertSame('1175000.00', $booking->total_amount);
    }

    public function test_booking_rejects_an_occupied_seat(): void
    {
        $user = User::factory()->create();
        $flight = $this->createFlight();
        $existingBooking = Booking::create([
            'booking_code' => 'BK-EXISTING',
            'user_id' => $user->id,
            'flight_id' => $flight->id,
            'seat_class' => 'Economy',
            'total_amount' => 1000000,
            'status' => 'pending',
        ]);
        $existingBooking->passengers()->create([
            'full_name' => 'Existing Passenger',
            'passport_number' => 'B12345678',
            'passport_expiry' => now()->addYear()->toDateString(),
            'nationality' => 'Indonesia',
            'seat_number' => '1A',
        ]);

        $response = $this->actingAs($user)->post(route('booking.store'), [
            'flight_id' => $flight->id,
            'seat_class' => 'Economy',
            'passengers' => [[
                'full_name' => 'New Passenger',
                'passport_number' => 'C12345678',
                'passport_expiry' => now()->addYear()->toDateString(),
                'nationality' => 'Indonesia',
                'seat_number' => '1A',
            ]],
        ]);

        $response->assertStatus(422);
        $this->assertSame(1, Booking::count());
    }

    public function test_expired_booking_is_cancelled_and_releases_reserved_seats(): void
    {
        $user = User::factory()->create();
        $flight = $this->createFlight();
        $booking = Booking::create([
            'booking_code' => 'BK-EXPIRED',
            'user_id' => $user->id,
            'flight_id' => $flight->id,
            'seat_class' => 'Economy',
            'total_amount' => 1000000,
            'payment_expires_at' => now()->subMinute(),
            'status' => 'pending',
        ]);
        $booking->passengers()->create([
            'full_name' => 'Expired Passenger',
            'passport_number' => 'D12345678',
            'passport_expiry' => now()->addYear()->toDateString(),
            'nationality' => 'Indonesia',
            'seat_number' => '1A',
        ]);
        $flight->update(['available_seats' => 19]);

        $response = $this->actingAs($user)->get(route('booking.checkout', $booking->id));

        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'cancelled']);
        $this->assertSame(20, $flight->fresh()->available_seats);
    }

    public function test_flight_search_applies_airline_transit_and_price_filters(): void
    {
        $this->createFlight();

        $response = $this->get(route('booking.create', [
            'airline' => ['GA'],
            'transit' => ['direct'],
            'max_price' => 1000000,
        ]));

        $response->assertOk();
        $response->assertSee('GA-001');
        $response->assertSee('Hasil Penerbangan (1)');
    }

    public function test_booking_page_can_render_the_expanded_flight_catalog(): void
    {
        $this->createFlight();

        $response = $this->get(route('booking.create'));

        $response->assertOk();
        $response->assertSee('GA-001');
        $response->assertSee('Jakarta');
        $response->assertSee('Singapore');
    }

    private function createFlight(): Flight
    {
        $airline = Airline::create(['code' => 'GA', 'name' => 'Garuda Indonesia']);
        $origin = Airport::create([
            'code' => 'CGK',
            'name' => 'Soekarno-Hatta International Airport',
            'city' => 'Jakarta',
            'country' => 'Indonesia',
        ]);
        $destination = Airport::create([
            'code' => 'SIN',
            'name' => 'Singapore Changi Airport',
            'city' => 'Singapore',
            'country' => 'Singapore',
        ]);

        return Flight::create([
            'flight_number' => 'GA-001',
            'airline_id' => $airline->id,
            'origin_airport_id' => $origin->id,
            'destination_airport_id' => $destination->id,
            'departure_time' => now()->addDays(2),
            'arrival_time' => now()->addDays(2)->addHours(2),
            'price' => 1000000,
            'total_seats' => 20,
            'available_seats' => 20,
        ]);
    }
}
