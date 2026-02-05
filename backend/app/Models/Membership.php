<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MembershipBenefit;
use App\Models\MembershipPlanBenefit;

class Membership extends Model
{
    use HasFactory;

    protected $table = 'membership';
    protected $hidden  = ['id'];
    protected $guarded = [];  

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('title', 'like', '%' . $search . '%')
                        ->orWhere('desc', 'like', '%' . $search . '%');
        }
        return $query;
    }

    public function benefits()
    {

        return $this->hasMany(MembershipPlanBenefit::class, 'odata_membership', 'odata');
    }

}
