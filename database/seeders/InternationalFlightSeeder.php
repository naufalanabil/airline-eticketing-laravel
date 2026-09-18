<?php

namespace Database\Seeders;

use App\Models\Airline;
use App\Models\Airport;
use App\Models\Flight;
use Illuminate\Database\Seeder;

class InternationalFlightSeeder extends Seeder
{
    public function run(): void
    {
        $airlines = [
            ['code' => 'TG', 'name' => 'Thai Airways'],
            ['code' => 'VN', 'name' => 'Vietnam Airlines'],
            ['code' => 'PR', 'name' => 'Philippine Airlines'],
            ['code' => 'SL', 'name' => 'Thai Lion Air'],
            ['code' => 'TR', 'name' => 'Scoot'],
            ['code' => 'QZ', 'name' => 'Indonesia AirAsia'],
            ['code' => 'OZ', 'name' => 'Asiana Airlines'],
            ['code' => 'CI', 'name' => 'China Airlines'],
            ['code' => 'BR', 'name' => 'EVA Air'],
            ['code' => 'CX', 'name' => 'Cathay Pacific'],
            ['code' => 'CZ', 'name' => 'China Southern'],
            ['code' => 'MU', 'name' => 'China Eastern'],
            ['code' => 'CA', 'name' => 'Air China'],
            ['code' => 'QR', 'name' => 'Qatar Airways'],
            ['code' => 'EY', 'name' => 'Etihad Airways'],
            ['code' => 'SV', 'name' => 'Saudia'],
            ['code' => 'WY', 'name' => 'Oman Air'],
            ['code' => 'TK', 'name' => 'Turkish Airlines'],
            ['code' => 'LH', 'name' => 'Lufthansa'],
            ['code' => 'AF', 'name' => 'Air France'],
            ['code' => 'BA', 'name' => 'British Airways'],
            ['code' => 'KL', 'name' => 'KLM Royal Dutch'],
            ['code' => 'VA', 'name' => 'Virgin Australia'],
            ['code' => 'JQ', 'name' => 'Jetstar Airways'],
            ['code' => 'NZ', 'name' => 'Air New Zealand'],
            ['code' => 'UA', 'name' => 'United Airlines'],
            ['code' => 'DL', 'name' => 'Delta Air Lines'],
            ['code' => 'AA', 'name' => 'American Airlines'],
            ['code' => 'AC', 'name' => 'Air Canada'],
            ['code' => 'AK', 'name' => 'AirAsia'],
        ];

        foreach ($airlines as $airlineData) {
            $airline = Airline::firstOrNew(['code' => $airlineData['code']]);
            $airline->name = $airlineData['name'];
            $airline->save();
        }

        $airports = [
            ['code' => 'YIA', 'name' => 'Yogyakarta International Airport', 'city' => 'Yogyakarta', 'country' => 'Indonesia'],
            ['code' => 'PEN', 'name' => 'Penang International Airport', 'city' => 'Penang', 'country' => 'Malaysia'],
            ['code' => 'MED', 'name' => 'Prince Mohammad bin Abdulaziz International Airport', 'city' => 'Madinah', 'country' => 'Saudi Arabia'],
            ['code' => 'MCT', 'name' => 'Muscat International Airport', 'city' => 'Muscat', 'country' => 'Oman'],
            ['code' => 'CAN', 'name' => 'Guangzhou Baiyun International Airport', 'city' => 'Guangzhou', 'country' => 'China'],
            ['code' => 'DOH', 'name' => 'Hamad International Airport', 'city' => 'Doha', 'country' => 'Qatar'],
            ['code' => 'AUH', 'name' => 'Zayed International Airport', 'city' => 'Abu Dhabi', 'country' => 'United Arab Emirates'],
            ['code' => 'AMS', 'name' => 'Amsterdam Airport Schiphol', 'city' => 'Amsterdam', 'country' => 'Netherlands'],
            ['code' => 'IST', 'name' => 'Istanbul Airport', 'city' => 'Istanbul', 'country' => 'Turkey'],
            ['code' => 'FRA', 'name' => 'Frankfurt Airport', 'city' => 'Frankfurt', 'country' => 'Germany'],
            ['code' => 'CDG', 'name' => 'Charles de Gaulle Airport', 'city' => 'Paris', 'country' => 'France'],
            ['code' => 'LHR', 'name' => 'London Heathrow Airport', 'city' => 'London', 'country' => 'United Kingdom'],
            ['code' => 'MEL', 'name' => 'Melbourne Airport', 'city' => 'Melbourne', 'country' => 'Australia'],
            ['code' => 'PER', 'name' => 'Perth Airport', 'city' => 'Perth', 'country' => 'Australia'],
            ['code' => 'AKL', 'name' => 'Auckland Airport', 'city' => 'Auckland', 'country' => 'New Zealand'],
            ['code' => 'SFO', 'name' => 'San Francisco International Airport', 'city' => 'San Francisco', 'country' => 'United States'],
            ['code' => 'LAX', 'name' => 'Los Angeles International Airport', 'city' => 'Los Angeles', 'country' => 'United States'],
            ['code' => 'JFK', 'name' => 'John F. Kennedy International Airport', 'city' => 'New York', 'country' => 'United States'],
            ['code' => 'YVR', 'name' => 'Vancouver International Airport', 'city' => 'Vancouver', 'country' => 'Canada'],
        ];

        foreach ($airports as $airportData) {
            $airport = Airport::firstOrNew(['code' => $airportData['code']]);
            $airport->fill($airportData);
            $airport->save();
        }

        $flights = [
            ['number' => 'TG-434', 'airline' => 'TG', 'from' => 'CGK', 'to' => 'BKK', 'price' => 3200000, 'stops' => 0],
            ['number' => 'VN-630', 'airline' => 'VN', 'from' => 'CGK', 'to' => 'SGN', 'price' => 2900000, 'stops' => 0],
            ['number' => 'PR-536', 'airline' => 'PR', 'from' => 'CGK', 'to' => 'MNL', 'price' => 3100000, 'stops' => 0],
            ['number' => 'SL-117', 'airline' => 'SL', 'from' => 'SUB', 'to' => 'DMK', 'price' => 1800000, 'stops' => 0],
            ['number' => 'TR-263', 'airline' => 'TR', 'from' => 'YIA', 'to' => 'SIN', 'price' => 1350000, 'stops' => 0],
            ['number' => 'QZ-502', 'airline' => 'QZ', 'from' => 'KNO', 'to' => 'PEN', 'price' => 850000, 'stops' => 0],
            ['number' => 'OZ-762', 'airline' => 'OZ', 'from' => 'CGK', 'to' => 'ICN', 'price' => 6700000, 'stops' => 0],
            ['number' => 'CI-762', 'airline' => 'CI', 'from' => 'CGK', 'to' => 'TPE', 'price' => 4800000, 'stops' => 0],
            ['number' => 'BR-238', 'airline' => 'BR', 'from' => 'DPS', 'to' => 'TPE', 'price' => 5200000, 'stops' => 0],
            ['number' => 'CX-798', 'airline' => 'CX', 'from' => 'CGK', 'to' => 'HKG', 'price' => 4500000, 'stops' => 0],
            ['number' => 'CX-784', 'airline' => 'CX', 'from' => 'DPS', 'to' => 'HKG', 'price' => 4900000, 'stops' => 0],
            ['number' => 'CZ-3038', 'airline' => 'CZ', 'from' => 'CGK', 'to' => 'CAN', 'price' => 4200000, 'stops' => 1],
            ['number' => 'MU-5070', 'airline' => 'MU', 'from' => 'CGK', 'to' => 'PVG', 'price' => 4600000, 'stops' => 1],
            ['number' => 'CA-978', 'airline' => 'CA', 'from' => 'CGK', 'to' => 'PEK', 'price' => 5100000, 'stops' => 1],
            ['number' => 'EK-399', 'airline' => 'EK', 'from' => 'DPS', 'to' => 'DXB', 'price' => 9300000, 'stops' => 0],
            ['number' => 'QR-957', 'airline' => 'QR', 'from' => 'CGK', 'to' => 'DOH', 'price' => 9100000, 'stops' => 0],
            ['number' => 'QR-963', 'airline' => 'QR', 'from' => 'DPS', 'to' => 'DOH', 'price' => 9500000, 'stops' => 0],
            ['number' => 'EY-475', 'airline' => 'EY', 'from' => 'CGK', 'to' => 'AUH', 'price' => 8400000, 'stops' => 0],
            ['number' => 'SV-817', 'airline' => 'SV', 'from' => 'CGK', 'to' => 'MED', 'price' => 12800000, 'stops' => 1],
            ['number' => 'SV-826', 'airline' => 'SV', 'from' => 'SUB', 'to' => 'JED', 'price' => 13100000, 'stops' => 1],
            ['number' => 'WY-848', 'airline' => 'WY', 'from' => 'CGK', 'to' => 'MCT', 'price' => 7900000, 'stops' => 0],
            ['number' => 'GA-088', 'airline' => 'GA', 'from' => 'CGK', 'to' => 'AMS', 'price' => 14200000, 'stops' => 1],
            ['number' => 'TK-057', 'airline' => 'TK', 'from' => 'CGK', 'to' => 'IST', 'price' => 10500000, 'stops' => 0],
            ['number' => 'TK-067', 'airline' => 'TK', 'from' => 'DPS', 'to' => 'IST', 'price' => 11200000, 'stops' => 0],
            ['number' => 'LH-779', 'airline' => 'LH', 'from' => 'CGK', 'to' => 'FRA', 'price' => 13800000, 'stops' => 1],
            ['number' => 'AF-253', 'airline' => 'AF', 'from' => 'CGK', 'to' => 'CDG', 'price' => 14500000, 'stops' => 1],
            ['number' => 'BA-034', 'airline' => 'BA', 'from' => 'CGK', 'to' => 'LHR', 'price' => 15100000, 'stops' => 1],
            ['number' => 'KL-836', 'airline' => 'KL', 'from' => 'CGK', 'to' => 'AMS', 'price' => 13900000, 'stops' => 1],
            ['number' => 'QF-042', 'airline' => 'QF', 'from' => 'CGK', 'to' => 'SYD', 'price' => 6400000, 'stops' => 0],
            ['number' => 'VA-082', 'airline' => 'VA', 'from' => 'DPS', 'to' => 'MEL', 'price' => 5400000, 'stops' => 0],
            ['number' => 'JQ-107', 'airline' => 'JQ', 'from' => 'DPS', 'to' => 'PER', 'price' => 2800000, 'stops' => 0],
            ['number' => 'NZ-283', 'airline' => 'NZ', 'from' => 'DPS', 'to' => 'AKL', 'price' => 8700000, 'stops' => 0],
            ['number' => 'UA-178', 'airline' => 'UA', 'from' => 'CGK', 'to' => 'SFO', 'price' => 16500000, 'stops' => 1],
            ['number' => 'DL-168', 'airline' => 'DL', 'from' => 'CGK', 'to' => 'LAX', 'price' => 17200000, 'stops' => 1],
            ['number' => 'AA-180', 'airline' => 'AA', 'from' => 'CGK', 'to' => 'JFK', 'price' => 18500000, 'stops' => 1],
            ['number' => 'AC-016', 'airline' => 'AC', 'from' => 'CGK', 'to' => 'YVR', 'price' => 16900000, 'stops' => 1],
            ['number' => 'AK-505', 'airline' => 'AK', 'from' => 'SUB', 'to' => 'KUL', 'price' => 1450000, 'stops' => 0],
            ['number' => 'SQ-965', 'airline' => 'SQ', 'from' => 'KNO', 'to' => 'SIN', 'price' => 2200000, 'stops' => 0],
            ['number' => 'GA-612', 'airline' => 'GA', 'from' => 'CGK', 'to' => 'MNL', 'price' => 3300000, 'stops' => 1],
        ];

        foreach ($flights as $flightData) {
            $flight = Flight::firstOrNew(['flight_number' => $flightData['number']]);
            $departureTime = now()->addDays(3)->setTime(8, 0);
            $flight->fill([
                'airline_id' => Airline::where('code', $flightData['airline'])->valueOrFail('id'),
                'origin_airport_id' => Airport::where('code', $flightData['from'])->valueOrFail('id'),
                'destination_airport_id' => Airport::where('code', $flightData['to'])->valueOrFail('id'),
                'departure_time' => $departureTime,
                'arrival_time' => $departureTime->copy()->addHours(4),
                'stops' => $flightData['stops'],
                'price' => $flightData['price'],
                'total_seats' => 180,
            ]);

            if (! $flight->exists) {
                $flight->available_seats = 180;
            }

            $flight->save();
        }
    }
}
