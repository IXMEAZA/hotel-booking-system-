<?php

namespace App\Models;

use App\Models\RoomType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;
     protected $fillable = [
        'room_number',
        'room_type_id',
        'floor',
        'status',
        'is_active',
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
    
    public function bookings()
{
    return $this->belongsToMany(Booking::class, 'booking_rooms')
        ->withPivot(['price_per_night','nights','line_total'])
        ->withTimestamps();
}

public function scopeAvailableBetween($query, $checkIn, $checkOut, $excludeBookingId = null)
{
    return $query
        ->where('is_active', true)
        ->where('status', '!=', 'maintenance')
        ->whereDoesntHave('bookings', function ($q) use ($checkIn, $checkOut, $excludeBookingId) {
            $q->whereIn('status', ['pending','confirmed'])
              ->where('check_in_date', '<', $checkOut)
              ->where('check_out_date', '>', $checkIn);

            if ($excludeBookingId) {
                $q->where('bookings.id', '!=', $excludeBookingId);
            }
        });
}
   public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRoomType($query, int $roomTypeId)
    {
        return $query->where('room_type_id', $roomTypeId);
    }

    public function scopeFloor($query, int $floor)
    {
        return $query->where('floor', $floor);
    }
}