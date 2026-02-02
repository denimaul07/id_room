<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Socials;

class ContactMe extends Model
{
    use HasFactory;
    protected $table   = 'contact_me';
    protected $hidden  = ['id'];
    protected $guarded = [];  



}
