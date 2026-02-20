<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Membership;
use App\Models\UserMembership;
use App\Models\User;

class MembershipTransactions extends Model
{
    use HasFactory;

    protected $table = 'membership_transactions';
    protected $hidden  = ['id','user_id','membership_id'];
    protected $guarded = [];  

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('invoice_code', 'like', '%' . $search . '%');
        }
        return $query;
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function userMembership()    
    {
        return $this->belongsTo(UserMembership::class, 'user_membership_id');
    }

    public function user()    
    {
        return $this->belongsTo(User::class);
    }
}
