<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facilities extends Model
{
    use HasFactory;

    protected $table = 'facilities';
    protected $hidden = ['id'];
    protected $guarded = [];
    protected $casts = [
        'type' => 'array'
    ];

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('name', 'like', "%$search%")
                ->orWhere('type', 'like', "%$search%");
        }
        return $query;
    }
}
