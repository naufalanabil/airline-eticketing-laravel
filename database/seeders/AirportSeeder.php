<?php

namespace Database\Seeders;

use App\Models\Airport;
use Illuminate\Database\Seeder;

class AirportSeeder extends Seeder
{
    public function run(): void
    {
        $airports = [
            // --- ASIA TENGGARA ---
            ['code' => 'CGK', 'name' => 'Soekarno-Hatta International Airport', 'city' => 'Jakarta', 'country' => 'Indonesia'],
            ['code' => 'DPS', 'name' => 'Ngurah Rai International Airport', 'city' => 'Bali', 'country' => 'Indonesia'],
            ['code' => 'SUB', 'name' => 'Juanda International Airport', 'city' => 'Surabaya', 'country' => 'Indonesia'],
            ['code' => 'KNO', 'name' => 'Kualanamu International Airport', 'city' => 'Medan', 'country' => 'Indonesia'],
            ['code' => 'SIN', 'name' => 'Singapore Changi Airport', 'city' => 'Singapore', 'country' => 'Singapore'],
            ['code' => 'KUL', 'name' => 'Kuala Lumpur International Airport', 'city' => 'Kuala Lumpur', 'country' => 'Malaysia'],
            ['code' => 'BKK', 'name' => 'Suvarnabhumi Airport', 'city' => 'Bangkok', 'country' => 'Thailand'],
            ['code' => 'DMK', 'name' => 'Don Mueang International Airport', 'city' => 'Bangkok', 'country' => 'Thailand'],
            ['code' => 'MNL', 'name' => 'Ninoy Aquino International Airport', 'city' => 'Manila', 'country' => 'Philippines'],
            ['code' => 'SGN', 'name' => 'Tan Son Nhat International Airport', 'city' => 'Ho Chi Minh City', 'country' => 'Vietnam'],
            ['code' => 'HAN', 'name' => 'Noi Bai International Airport', 'city' => 'Hanoi', 'country' => 'Vietnam'],

            // --- ASIA TIMUR & SELATAN ---
            ['code' => 'HND', 'name' => 'Tokyo Haneda Airport', 'city' => 'Tokyo', 'country' => 'Japan'],
            ['code' => 'NRT', 'name' => 'Narita International Airport', 'city' => 'Tokyo', 'country' => 'Japan'],
            ['code' => 'KIX', 'name' => 'Kansai International Airport', 'city' => 'Osaka', 'country' => 'Japan'],
            ['code' => 'ICN', 'name' => 'Incheon International Airport', 'city' => 'Seoul', 'country' => 'South Korea'],
            ['code' => 'HKG', 'name' => 'Hong Kong International Airport', 'city' => 'Hong Kong', 'country' => 'Hong Kong'],
            ['code' => 'TPE', 'name' => 'Taiwan Taoyuan International Airport', 'city' => 'Taipei', 'country' => 'Taiwan'],
            ['code' => 'PEK', 'name' => 'Beijing Capital International Airport', 'city' => 'Beijing', 'country' => 'China'],
            ['code' => 'PVG', 'name' => 'Shanghai Pudong International Airport', 'city' => 'Shanghai', 'country' => 'China'],
            ['code' => 'DEL', 'name' => 'Indira Gandhi International Airport', 'city' => 'New Delhi', 'country' => 'India'],

            // --- TIMUR TENGAH ---
            ['code' => 'DXB', 'name' => 'Dubai International Airport', 'city' => 'Dubai', 'country' => 'United Arab Emirates'],
            ['code' => 'AUH', 'name' => 'Zayed International Airport', 'city' => 'Abu Dhabi', 'country' => 'United Arab Emirates'],
            ['code' => 'DOH', 'name' => 'Hamad International Airport', 'city' => 'Doha', 'country' => 'Qatar'],
            ['code' => 'JED', 'name' => 'King Abdulaziz International Airport', 'city' => 'Jeddah', 'country' => 'Saudi Arabia'],
            ['code' => 'RUH', 'name' => 'King Khalid International Airport', 'city' => 'Riyadh', 'country' => 'Saudi Arabia'],
            ['code' => 'IST', 'name' => 'Istanbul Airport', 'city' => 'Istanbul', 'country' => 'Turkey'],

            // --- EROPA ---
            ['code' => 'LHR', 'name' => 'London Heathrow Airport', 'city' => 'London', 'country' => 'United Kingdom'],
            ['code' => 'CDG', 'name' => 'Charles de Gaulle Airport', 'city' => 'Paris', 'country' => 'France'],
            ['code' => 'AMS', 'name' => 'Amsterdam Airport Schiphol', 'city' => 'Amsterdam', 'country' => 'Netherlands'],
            ['code' => 'FRA', 'name' => 'Frankfurt Airport', 'city' => 'Frankfurt', 'country' => 'Germany'],
            ['code' => 'MUC', 'name' => 'Munich Airport', 'city' => 'Munich', 'country' => 'Germany'],
            ['code' => 'MAD', 'name' => 'Adolfo Suárez Madrid–Barajas Airport', 'city' => 'Madrid', 'country' => 'Spain'],
            ['code' => 'BCN', 'name' => 'Josep Tarradellas Barcelona-El Prat Airport', 'city' => 'Barcelona', 'country' => 'Spain'],
            ['code' => 'FCO', 'name' => 'Leonardo da Vinci–Fiumicino Airport', 'city' => 'Rome', 'country' => 'Italy'],
            ['code' => 'ZRH', 'name' => 'Zurich Airport', 'city' => 'Zurich', 'country' => 'Switzerland'],

            // --- AMERIKA UTARA & LATIN ---
            ['code' => 'JFK', 'name' => 'John F. Kennedy International Airport', 'city' => 'New York', 'country' => 'United States'],
            ['code' => 'LAX', 'name' => 'Los Angeles International Airport', 'city' => 'Los Angeles', 'country' => 'United States'],
            ['code' => 'ORD', 'name' => 'O\'Hare International Airport', 'city' => 'Chicago', 'country' => 'United States'],
            ['code' => 'SFO', 'name' => 'San Francisco International Airport', 'city' => 'San Francisco', 'country' => 'United States'],
            ['code' => 'YVR', 'name' => 'Vancouver International Airport', 'city' => 'Vancouver', 'country' => 'Canada'],
            ['code' => 'YYZ', 'name' => 'Toronto Pearson International Airport', 'city' => 'Toronto', 'country' => 'Canada'],
            ['code' => 'MEX', 'name' => 'Mexico City International Airport', 'city' => 'Mexico City', 'country' => 'Mexico'],
            ['code' => 'GRU', 'name' => 'São Paulo/Guarulhos International Airport', 'city' => 'São Paulo', 'country' => 'Brazil'],

            // --- AUSTRALIA & PASIFIK ---
            ['code' => 'SYD', 'name' => 'Sydney Kingsford Smith Airport', 'city' => 'Sydney', 'country' => 'Australia'],
            ['code' => 'MEL', 'name' => 'Melbourne Airport', 'city' => 'Melbourne', 'country' => 'Australia'],
            ['code' => 'PER', 'name' => 'Perth Airport', 'city' => 'Perth', 'country' => 'Australia'],
            ['code' => 'AKL', 'name' => 'Auckland Airport', 'city' => 'Auckland', 'country' => 'New Zealand'],

            // --- AFRIKA ---
            ['code' => 'CAI', 'name' => 'Cairo International Airport', 'city' => 'Cairo', 'country' => 'Egypt'],
            ['code' => 'JNB', 'name' => 'O. R. Tambo International Airport', 'city' => 'Johannesburg', 'country' => 'South Africa'],
            ['code' => 'CPT', 'name' => 'Cape Town International Airport', 'city' => 'Cape Town', 'country' => 'South Africa'],
        ];

        foreach ($airports as $airport) {
            Airport::updateOrCreate(
                ['code' => $airport['code']],
                $airport,
            );
        }
    }
}
