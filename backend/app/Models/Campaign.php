<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CampaignContact;

class Campaign extends Model
{
    use HasFactory;
    protected $table = 'campaign';
    protected $hidden = ['id'];
    protected $guarded = [];

    public function contacts()
    {
        return $this->hasMany(CampaignContact::class, 'campaign_id');
    }

}
