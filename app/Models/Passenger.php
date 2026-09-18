<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    protected $fillable = [
        'booking_id', 'full_name', 'passport_number',
        'passport_expiry', 'nationality', 'seat_number',
    ];

    protected function casts(): array
    {
        return [
            'passport_expiry' => 'date',
        ];
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
