<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignContact extends Model
{
    use HasFactory;
    protected $table = 'campaign_contacts';
    protected $hidden = ['id','campaign_id'];
    protected $guarded = [];

}
