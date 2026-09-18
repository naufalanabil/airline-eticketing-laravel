<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $fillable = ['booking_id', 'order_id', 'transaction_status', 'gross_amount', 'payload'];

    protected function casts(): array
    {
        return [
            'gross_amount' => 'decimal:2',
            'payload' => 'array',
        ];
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
