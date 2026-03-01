<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CrmHistory;
use App\Models\User;

class CRM extends Model
{
    use HasFactory;
    protected $table   = 'leads';
    protected $hidden  = ['id','assigned_id'];
    protected $guarded = [];  

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status', $status);
        });

        $query->when($filters['start_date'] ?? null, function ($query, $start_date) {
            $query->whereDate('tanggal_leads', '>=', $start_date);
        });

        $query->when($filters['end_date'] ?? null, function ($query, $end_date) {
            $query->whereDate('tanggal_leads', '<=', $end_date);
        });
    }

    public function history()
    {
        return $this->hasMany(CrmHistory::class, 'leads_id', 'id');
    }

    public function assigned()
    {
        return $this->belongsTo(User::class, 'assigned_id', 'id');
    }

}
