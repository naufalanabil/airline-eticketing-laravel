<?php

namespace Database\Seeders;

use App\Models\Airline;
use App\Models\Airport;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
        ]);

        $airlines = [
            ['code' => 'GA', 'name' => 'Garuda Indonesia', 'logo' => 'https://www.gstatic.com/flights/airline_logos/70px/GA.png'],
            ['code' => 'SQ', 'name' => 'Singapore Airlines', 'logo' => 'https://www.gstatic.com/flights/airline_logos/70px/SQ.png'],
            ['code' => 'AK', 'name' => 'AirAsia', 'logo' => 'https://www.gstatic.com/flights/airline_logos/70px/AK.png'],
            ['code' => 'JL', 'name' => 'Japan Airlines', 'logo' => 'https://www.gstatic.com/flights/airline_logos/70px/JL.png'],
            ['code' => 'MH', 'name' => 'Malaysia Airlines', 'logo' => 'https://www.gstatic.com/flights/airline_logos/70px/MH.png'],
            ['code' => 'EK', 'name' => 'Emirates', 'logo' => 'https://www.gstatic.com/flights/airline_logos/70px/EK.png'],
            ['code' => 'KE', 'name' => 'Korean Air', 'logo' => 'https://www.gstatic.com/flights/airline_logos/70px/KE.png'],
            ['code' => 'QF', 'name' => 'Qantas Airways', 'logo' => 'https://www.gstatic.com/flights/airline_logos/70px/QF.png'],
            ['code' => 'QR', 'name' => 'Qatar Airways', 'logo' => 'https://www.gstatic.com/flights/airline_logos/70px/QR.png'],
            ['code' => 'NH', 'name' => 'ANA (All Nippon Airways)', 'logo' => 'https://www.gstatic.com/flights/airline_logos/70px/NH.png'],
        ];

        foreach ($airlines as $airline) {
            Airline::updateOrCreate(['code' => $airline['code']], $airline);
        }

        $this->call(AirportSeeder::class);
        $this->call(InternationalFlightSeeder::class);
        $this->call(VoucherSeeder::class);

        $airlineId = fn (string $code): int => Airline::where('code', $code)->value('id');
        $airportId = fn (string $code): int => Airport::where('code', $code)->value('id');

        $flights = [
            ['flight_number' => 'GA-828', 'airline' => 'GA', 'from' => 'CGK', 'to' => 'SIN', 'departure' => [1, 8, 45], 'arrival' => [1, 11, 35], 'price' => 2100000, 'stops' => 0],
            ['flight_number' => 'SQ-951', 'airline' => 'SQ', 'from' => 'CGK', 'to' => 'SIN', 'departure' => [1, 5, 25], 'arrival' => [1, 8, 10], 'price' => 2850000, 'stops' => 0],
            ['flight_number' => 'AK-381', 'airline' => 'AK', 'from' => 'CGK', 'to' => 'KUL', 'departure' => [2, 10, 15], 'arrival' => [2, 13, 20], 'price' => 1250000, 'stops' => 0],
            ['flight_number' => 'MH-712', 'airline' => 'MH', 'from' => 'CGK', 'to' => 'KUL', 'departure' => [2, 15, 0], 'arrival' => [2, 18, 5], 'price' => 1750000, 'stops' => 0],
            ['flight_number' => 'JL-726', 'airline' => 'JL', 'from' => 'CGK', 'to' => 'HND', 'departure' => [3, 21, 55], 'arrival' => [4, 7, 25], 'price' => 7800000, 'stops' => 0],
            ['flight_number' => 'KE-628', 'airline' => 'KE', 'from' => 'CGK', 'to' => 'ICN', 'departure' => [3, 22, 5], 'arrival' => [4, 7, 15], 'price' => 6900000, 'stops' => 0],
            ['flight_number' => 'EK-357', 'airline' => 'EK', 'from' => 'CGK', 'to' => 'DXB', 'departure' => [4, 17, 40], 'arrival' => [4, 22, 55], 'price' => 8900000, 'stops' => 0],
            ['flight_number' => 'GA-980', 'airline' => 'GA', 'from' => 'CGK', 'to' => 'JED', 'departure' => [5, 11, 30], 'arrival' => [5, 17, 30], 'price' => 13500000, 'stops' => 1],
            ['flight_number' => 'SQ-943', 'airline' => 'SQ', 'from' => 'DPS', 'to' => 'SIN', 'departure' => [1, 13, 0], 'arrival' => [1, 15, 40], 'price' => 2400000, 'stops' => 0],
            ['flight_number' => 'QF-044', 'airline' => 'QF', 'from' => 'DPS', 'to' => 'SYD', 'departure' => [2, 22, 10], 'arrival' => [3, 6, 15], 'price' => 5800000, 'stops' => 0],
            ['flight_number' => 'AK-302', 'airline' => 'AK', 'from' => 'DPS', 'to' => 'BKK', 'departure' => [2, 14, 20], 'arrival' => [2, 17, 50], 'price' => 1950000, 'stops' => 0],
            ['flight_number' => 'GA-880', 'airline' => 'GA', 'from' => 'DPS', 'to' => 'HND', 'departure' => [4, 0, 25], 'arrival' => [4, 8, 50], 'price' => 7400000, 'stops' => 0],
        ];

        foreach ($flights as $flight) {
            $totalSeats = 180;
            $departure = now()->addDays($flight['departure'][0])->setTime($flight['departure'][1], $flight['departure'][2]);
            $arrival = now()->addDays($flight['arrival'][0])->setTime($flight['arrival'][1], $flight['arrival'][2]);

            Flight::updateOrCreate(
                ['flight_number' => $flight['flight_number']],
                [
                    'airline_id' => $airlineId($flight['airline']),
                    'origin_airport_id' => $airportId($flight['from']),
                    'destination_airport_id' => $airportId($flight['to']),
                    'departure_time' => $departure,
                    'arrival_time' => $arrival,
                    'stops' => $flight['stops'],
                    'price' => $flight['price'],
                    'total_seats' => $totalSeats,
                    'available_seats' => $totalSeats,
                ],
            );
        }
    }
}
