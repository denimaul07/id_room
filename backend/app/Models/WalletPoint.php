<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\PointTransactions;

class WalletPoint extends Model
{
    use HasFactory;

    protected $table   = 'wallets_point';
    protected $hidden  = ['id','user_id','reference_id'];
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function point_transactions()
    {
        return $this->hasOne(PointTransactions::class, 'id', 'reference_id');
    }


}