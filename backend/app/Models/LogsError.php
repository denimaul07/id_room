<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogsError extends Model
{
    use HasFactory;

    protected $table   = 'logs_error';
    protected $guarded = ['id'];

}
