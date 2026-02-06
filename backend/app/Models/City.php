<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Properties;

class City extends Model
{
    use HasFactory;
    protected $table = 'city';
    protected $hidden = ['id', 'id_province'];
    protected $guarded = [];

    public function properties()
    {
        return $this->hasMany(Properties::class, 'city', 'odata');
    }

}
