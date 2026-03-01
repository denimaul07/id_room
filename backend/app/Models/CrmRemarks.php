<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmRemarks extends Model
{
    use HasFactory;
    protected $table   = 'leads_remark_options';
    protected $guarded = [];  



}
