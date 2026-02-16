<?php

namespace App\Models;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Properties extends Model
{
    use HasFactory;

    protected $table = 'properties';
    protected $hidden = ['id'];
    protected $guarded = [];

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query
                ->where('properties', 'like', "%$search%")
                ->orWhere('type', 'like', "%$search%")
                ->orWhere('address', 'like', "%$search%")
                ->orWhere('city', 'like', "%$search%")
                ->orWhere('province', 'like', "%$search%");
        }
        return $query;
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province', 'odata');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city', 'odata');
    }

    public function facilities()
    {
        return $this->hasMany(PropertyFacilities::class, 'property_odata', 'odata');
    }

    public function gallery()
    {
        return $this->hasMany(PropertyGallery::class, 'property_odata', 'odata');
    }

    public function rooms()
    {
        return $this->hasMany(Rooms::class, 'property_odata', 'odata');
    }
}
