<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomFacilities extends Model
{
    use HasFactory;

    protected $table = 'room_facilities';
    protected $hidden = ['id', 'room_id', 'facility_id'];
    protected $guarded = [];

    public function room()
    {
        return $this->belongsTo(Rooms::class, 'room_odata', 'odata');
    }

    public function facility()
    {
        return $this->belongsTo(Facilities::class, 'facility_odata', 'odata');
    }
}
