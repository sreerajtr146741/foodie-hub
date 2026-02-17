<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantTable extends Model
{
    protected $fillable = [
        'table_number',
        'type',
        'capacity',
        'status',
    ];

    public static function getAvailableOnlineCount()
    {
        return self::where('type', 'online')
            ->where('status', 'available')
            ->count();
    }
}
