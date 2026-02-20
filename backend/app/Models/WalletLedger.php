<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TopupTransactions;
use App\Models\MembershipTransaction;
use App\Models\BookingPayment;

class WalletLedger extends Model
{
    use HasFactory;

    protected $table   = 'wallet_ledger';
    protected $hidden  = ['id','user_id','reference_id'];
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topup_transactions()
    {
        return $this->hasOne(TopupTransactions::class, 'id', 'reference_id');
    }

    public function membership_transactions()
    {
        return $this->hasOne(MembershipTransaction::class, 'id', 'reference_id');
    }

    public function booking_payments()
    {
        return $this->hasOne(BookingPayment::class, 'id', 'reference_id');
    }
}