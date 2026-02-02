<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessWork extends Model
{
    use HasFactory;
    protected $table   = 'processwork';
    protected $hidden  = ['id'];
    protected $guarded = [];
}
