<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Properties;
use App\Models\City;

class Properties extends Model
{
    use HasFactory;
    protected $table = 'properties';
    protected $hidden = ['id'];
    protected $guarded = [];

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('properties', 'like', "%$search%")
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

}
