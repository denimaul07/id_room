<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\MembershipTransactions;
use App\Models\BookingPayment;
use App\Models\TopupTransactions;

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

    public function membershipTransactions()
    {
        return $this->belongsTo(MembershipTransactions::class, 'reference_id');
    }

    public function bookingPayments()
    {
        return $this->belongsTo(BookingPayment::class, 'reference_id');
    }

    public function topup()
    {
        return $this->belongsTo(TopupTransactions::class, 'reference_id');
    }
}
