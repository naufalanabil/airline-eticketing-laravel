<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = ['code', 'type', 'amount', 'max_uses', 'valid_until'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'max_uses' => 'integer',
            'valid_until' => 'date',
        ];
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
