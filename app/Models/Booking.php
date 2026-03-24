<?php

namespace App\Models;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
     protected $fillable = [
        'booking_code',
        'user_id',
        'check_in_date',
        'check_out_date',
        'guest_name',
        'guest_phone',
        'payment_method',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'meals_total',
        'payment_method',
        'adults_count',
        'children_count',
        'status',
        'created_by',
        'modified_by',
        'notes',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',      
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'booking_rooms')
            ->withPivot(['price_per_night','nights','line_total'])
            ->withTimestamps();
    }

    public function customer()
{
    return $this->user();
}

}
