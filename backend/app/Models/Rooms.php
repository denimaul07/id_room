<?php

namespace App\Models;

use App\Models\Properties;
use App\Models\RoomFacilities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    use HasFactory;

    protected $table = 'rooms';
    protected $hidden = ['id', 'property_id'];
    protected $guarded = [];

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query
                ->where('room_name', 'like', "%$search%")
                ->orWhere('room_type', 'like', "%$search%")
                ->orWhere('property_odata', 'like', "%$search%");
        }
        return $query;
    }

    public function property()
    {
        return $this->belongsTo(Properties::class, 'property_odata', 'odata');
    }

    public function facilities()
    {
        return $this->hasMany(RoomFacilities::class, 'room_odata', 'odata');
    }
}
