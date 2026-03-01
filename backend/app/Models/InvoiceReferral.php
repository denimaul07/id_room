<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceReferral extends Model
{
    use HasFactory;

    protected $table = 'invoice_referral';
    protected $guarded = [];
}
