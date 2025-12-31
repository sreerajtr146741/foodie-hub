<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'guests',
        'booking_date',
        'booking_time',
        'special_requests',
        'status',
        'table_type',
        'seating_preference',
        'window_side',
        'occasion',
        'table_number',
        'admin_notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'booking_time' => 'datetime:H:i',
        'window_side' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function foodOrders()
    {
        return $this->hasMany(BookingFoodOrder::class);
    }
}
