<?php

namespace App\Services\Points;

use App\Models\PointTransactions;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PointService
{
    public function list($dateFrom, $dateTo, $filter, $search, $paginate, $keyActive)
    {
        $users= Auth::user();
        $query = PointTransactions::orderBy('created_at', 'desc')
            ->where('user_id', $users->id)
            ->when($keyActive === 'month', function ($query) use ($filter) {
                if ($filter == '30') {
                    // 30: bulan ini
                    $query->whereMonth('created_at', Carbon::now()->month)
                        ->whereYear('created_at', Carbon::now()->year);
                } else if ($filter == '60') {
                    // 60: bulan lalu
                    $lastMonth = Carbon::now()->subMonth();
                    $query->whereMonth('created_at', $lastMonth->month)
                        ->whereYear('created_at', $lastMonth->year);
                } else if ($filter == '90') {
                    // 90: 90 hari terakhir
                    $query->whereDate('created_at', '>=', Carbon::now()->subDays(90)->toDateString());
                }
            })
            ->when($keyActive === 'date' && $dateFrom && $dateTo, function ($query) use ($dateFrom, $dateTo) {
                $query->whereDate('created_at', '>=', $dateFrom)
                        ->whereDate('created_at', '<=', $dateTo);
            })
            ->when($search, function ($query) use ($search) {
                $query->where('description', 'like', '%' . $search . '%');
            })
            ->paginate($paginate);
        
        return $query;
    }

}