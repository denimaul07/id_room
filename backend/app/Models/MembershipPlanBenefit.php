<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MembershipBenefit;

class MembershipPlanBenefit extends Model
{
    use HasFactory;

    protected $table = 'membership_plan_benefits';
    protected $hidden  = ['id'];
    protected $guarded = [];
    
    public function benefitDetails()
    {
        return $this->belongsTo(MembershipBenefit::class, 'odata_benefit', 'odata');
    }
}
