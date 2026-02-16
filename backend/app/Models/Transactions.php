<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\MembershipTransaction;

class Transactions extends Model
{
    use HasFactory;

    protected $table   = 'transactions';
    protected $hidden  = ['id','user_id','reference_id'];
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function membership()
    {
        return $this->belongsTo(MembershipTransaction::class, 'reference_id')
            ->where('transactions.type', 'MEMBERSHIP');
    }

    // public function booking()
    // {
    //     return $this->belongsTo(BookingTransaction::class, 'reference_id')
    //         ->where('transactions.type', 'BOOKING');
    // }

    // public function topup()
    // {
    //     return $this->belongsTo(TopupTransaction::class, 'reference_id')
    //         ->where('transactions.type', 'TOPUP');
    // }
}
