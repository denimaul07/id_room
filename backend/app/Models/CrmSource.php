<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmSource extends Model
{
    use HasFactory;
    protected $table   = 'leads_source';
    protected $hidden  = ['id'];
    protected $guarded = [];  



}
