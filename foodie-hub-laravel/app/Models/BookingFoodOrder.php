<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingFoodOrder extends Model
{
    protected $fillable = [
        'booking_id',
        'food_id',
        'quantity',
        'cooking_note',
        'food_status',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}
