<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_code', 'user_id', 'flight_id',
        'seat_class', 'baggage_weight', 'has_meal', 'has_insurance',
        'total_amount', 'addons_total', 'voucher_id', 'discount_amount',
        'payment_expires_at', 'status', 'snap_token', 'pdf_path',
    ];

    protected function casts(): array
    {
        return [
            'baggage_weight' => 'integer',
            'has_meal' => 'boolean',
            'has_insurance' => 'boolean',
            'total_amount' => 'decimal:2',
            'addons_total' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'payment_expires_at' => 'datetime',
        ];
    }

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function paymentLogs()
    {
        return $this->hasMany(PaymentLog::class);
    }

    public function passengers()
    {
        return $this->hasMany(Passenger::class);
    }
}
