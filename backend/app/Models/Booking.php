<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Properties;
use App\Models\Rooms;
use App\Models\Membership;
use App\Models\BookingPayment;
use App\Models\BookingGuest;

class Booking extends Model
{
    use HasFactory;
    protected $table   = 'bookings';
    protected $hidden  = ['id','user_id','property_id','room_id','membership_id'];
    protected $guarded = [];  


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function property()
    {
        return $this->belongsTo(Properties::class);
    }

    public function room()
    {
        return $this->belongsTo(Rooms::class);
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }


    public function payments()
    {
        return $this->hasMany(BookingPayment::class);
    }   

    public function passengers()
    {
        return $this->hasMany(BookingGuest::class);
    }
}
