<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral_Setting extends Model
{
    use HasFactory;
    protected $table = 'referral_settings';
    protected $hidden = ['id'];
    protected $guarded = [];



}
