<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyGallery extends Model
{
    use HasFactory;

    protected $table = 'property_gallery';
    protected $hidden = ['id', 'property_id'];
    protected $guarded = [];

    public function property()
    {
        return $this->belongsTo(Properties::class, 'property_odata', 'odata');
    }
}
