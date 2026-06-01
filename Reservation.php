<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'guest_id',
        'room_id',
        'check_in',
        'check_out',
        'total_price',
        'status',
        'user_id',
    ];

    /**
     * Връзка с госта — резервацията принадлежи на един гост
     */
    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    /**
     * Връзка със стаята — резервацията принадлежи на една стая
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Връзка с потребителя — кой е създал резервацията
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
