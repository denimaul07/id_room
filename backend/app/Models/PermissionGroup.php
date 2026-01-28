<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    use HasFactory;
    protected $guarded = [];  

    public function scopeSearch($query, $term){
        $term = "%$term%";

        $query->where(function($query) use ($term){
            $query->where('group','like', $term);
        });
    }
}
