<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;

class RoomSub extends Model
{
    use HasFactory;

    protected $table = 'room_subs';
    protected $hidden = ['id', 'id_room'];
    protected $guarded = [];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'room_sub_id', 'id');
    }
}