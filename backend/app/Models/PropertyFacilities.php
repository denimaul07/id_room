<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyFacilities extends Model
{
    use HasFactory;

    protected $table = 'property_facilities';
    protected $hidden = ['id', 'property_id', 'facility_id'];
    protected $guarded = [];

    public function property()
    {
        return $this->belongsTo(Properties::class, 'property_odata', 'odata');
    }

    public function facility()
    {
        return $this->belongsTo(Facilities::class, 'facility_odata', 'odata');
    }
}
