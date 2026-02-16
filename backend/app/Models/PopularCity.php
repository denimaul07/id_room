<?php

namespace App\Models;

use App\Models\City;
use App\Models\Properties;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopularCity extends Model
{
    use HasFactory;

    protected $table = 'popular_city';
    protected $hidden = ['id', 'id_city'];

    protected $fillable = [
        'odata',
        'odata_city',
        'image'
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'odata_city', 'odata');
    }

    public function properties()
    {
        return $this->hasMany(Properties::class, 'city', 'odata_city');
    }
}
