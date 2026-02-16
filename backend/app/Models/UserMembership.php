<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Membership;
use App\Models\MembershipTransactions;


class UserMembership extends Model
{
    use HasFactory;
    protected $table   = 'user_memberships';
    protected $hidden  = ['id','user_id','membership_id'];
    protected $guarded = [];  

    public function membership()
    {
        return $this->belongsTo(Membership::class, 'membership_id', 'id');  
    }

    public function transactions()
    {
        return $this->belongsTo(MembershipTransactions::class, 'transaction_id', 'id');
    }

}
