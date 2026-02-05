<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipBenefit extends Model
{
    use HasFactory;

    protected $table = 'membership_benefit';
    protected $hidden  = ['id'];
    protected $guarded = [];

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
        }
        return $query;
    }
}
