<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'id_number',
    ];

    /**
     * Връзка с резервациите — един гост има много резервации
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Пълното име на госта
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
