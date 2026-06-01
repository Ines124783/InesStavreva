<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'room_number',
        'type_id',
        'floor',
        'capacity',
        'price_per_night',
        'status',
    ];


    public function type()
    {
        return $this->belongsTo(RoomType::class, 'type_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }


    public function isAvailable($checkIn, $checkOut)
    {
        return !$this->reservations()
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                      ->orWhereBetween('check_out', [$checkIn, $checkOut]);
            })
            ->exists();
    }
}
