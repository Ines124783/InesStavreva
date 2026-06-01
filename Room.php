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

    /**
     * Връзка с типа стая
     */
    public function type()
    {
        return $this->belongsTo(RoomType::class, 'type_id');
    }

    /**
     * Връзка с резервациите — една стая има много резервации
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Проверка дали стаята е свободна за даден период
     */
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
