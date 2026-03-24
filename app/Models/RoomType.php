<?php

namespace App\Models;

use App\Models\Room;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    protected $fillable = [
        'name',
        'capacity_adults',
        'base_price_per_night',
        'description',
        'image',
        'is_active',
    ];
    public function rooms()
{
    return $this->hasMany(Room::class);
}

}
