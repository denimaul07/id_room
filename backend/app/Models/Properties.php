<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Properties;

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
}
