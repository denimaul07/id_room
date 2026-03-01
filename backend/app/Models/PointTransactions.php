<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PointTransactions extends Model
{
    use HasFactory;
    protected $table   = 'point_transaction';
    protected $hidden  = ['id'];
    protected $guarded = [];  

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('invoice_code', 'like', '%' . $search . '%');
        }
        return $query;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
