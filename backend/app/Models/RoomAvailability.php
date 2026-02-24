<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rooms;
use App\Models\Booking;

class RoomAvailability extends Model
{
    use HasFactory;
    protected $table = 'room_availability';
    protected $hidden = ['id', 'room_id'];
    protected $guarded = [];

    public function room()
    {
        return $this->belongsTo(Rooms::class, 'room_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

}
