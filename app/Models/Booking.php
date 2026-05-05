<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'guide_id',
        'destination_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'guest_name',
        'guest_email',
        'date',
        'notes',
        'start_date',
        'end_date',
        'num_days',
        'total_amount',
        'status',
        'stripe_session_id',
        'stripe_payment_intent_id',
    ];

    protected $casts = [
        'date'       => 'date',
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function guide()
    {
        return $this->belongsTo(Guide::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
