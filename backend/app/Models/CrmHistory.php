<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmHistory extends Model
{
    use HasFactory;
    protected $table   = 'leads_history';
    protected $hidden  = ['id'];
    protected $guarded = [];  



}
