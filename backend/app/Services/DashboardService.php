<?php
namespace App\Services;

use Carbon\Carbon;
use App\Models\Transactions;
use App\Models\User;
use App\Models\Membership;
use App\Models\Booking;

class DashboardService
{
    public function getOverview($page, $selectedFilter, $customStart, $customEnd, $pagging)
    {

        $startDate = null;
        $endDate   = null;

        if ($selectedFilter == 'today') {
            $startDate = Carbon::today()->startOfDay();
            $endDate   = Carbon::today()->endOfDay();

        } elseif ($selectedFilter == 'month') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate   = Carbon::now()->endOfMonth();

        } elseif ($selectedFilter == 'year') {
            $startDate = Carbon::now()->startOfYear();
            $endDate   = Carbon::now()->endOfYear();

        } elseif ($selectedFilter == 'custom') {
            $startDate = Carbon::parse($customStart)->startOfDay();
            $endDate   = Carbon::parse($customEnd)->endOfDay();

        } else {
            $startDate = Carbon::today()->startOfDay();
            $endDate   = Carbon::today()->endOfDay();
        }


        // 💰 Revenue Today: SUM(financial.amount) transaksi PAID hari ini
        $revenueToday = Transactions::whereBetween('created_at', [$startDate, $endDate])
        ->where('status', 'PAID')
        ->sum('amount');


        // 📥 Pending Payment: COUNT(status=PENDING)
        $pendingPayment = Transactions::where('status', 'PENDING')->count();

        // 👛 User Wallet Liability: SUM(users.balance)
        $userWalletLiability = User::where('status_users', 0)->sum('balance');

        // 🏨 Active Booking: COUNT(status=CONFIRMED)
        $activeBooking = Booking::where('status', 'CONFIRMED')->count();

        // 🎫 Active Membership: COUNT(active)
        $activeMembership = Membership::where('isActive', 0)->count();

        // 🔄 Refund This Month: SUM(refund)
        $refundThisMonth = Transactions::where('status', 'REFUNDED')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->sum('amount');


        // Grafik transaksi (harian, mingguan, bulanan)
        // Breakdown daily revenue by type
        $dailyRaw = Transactions::selectRaw('DATE(created_at) as date, type, SUM(amount) as total')
            ->where('status', 'PAID')
            ->whereBetween('created_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get();

        // Format: [{date, topup, booking, membership}]
        $daily = [];
        foreach ($dailyRaw as $row) {
            $date = $row->date;
            $type = strtoupper($row->type);
            if (!isset($daily[$date])) {
                $daily[$date] = [
                    'date' => $date,
                    'topup' => 0,
                    'booking' => 0,
                    'membership' => 0,
                ];
            }
            if ($type === 'TOPUP') {
                $daily[$date]['topup'] = (float)$row->total;
            } elseif ($type === 'BOOKING') {
                $daily[$date]['booking'] = (float)$row->total;
            } elseif ($type === 'MEMBERSHIP') {
                $daily[$date]['membership'] = (float)$row->total;
            }
        }
        // Re-index to numeric array, sort by date ascending
        // Sort $daily by date ascending (PHP native)
        usort($daily, function($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });
        $daily = array_values($daily);



        // Ambil data 7 hari terakhir breakdown by type
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $last7Days[$date] = [
                'date' => $date,
                'topup' => 0,
                'booking' => 0,
                'membership' => 0,
            ];
        }

        $weeklyRaw = Transactions::selectRaw('DATE(created_at) as date, type, SUM(amount) as total')
            ->where('status', 'PAID')
            ->whereBetween('created_at', [Carbon::today()->subDays(6)->startOfDay(), Carbon::today()->endOfDay()])
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get();

        foreach ($weeklyRaw as $row) {
            $date = $row->date;
            $type = strtoupper($row->type);
            if (isset($last7Days[$date])) {
                if ($type === 'TOPUP') {
                    $last7Days[$date]['topup'] = (float)$row->total;
                } elseif ($type === 'BOOKING') {
                    $last7Days[$date]['booking'] = (float)$row->total;
                } elseif ($type === 'MEMBERSHIP') {
                    $last7Days[$date]['membership'] = (float)$row->total;
                }
            }
        }

        // Re-index as numeric array
        $weekly = array_values($last7Days);


        // Ambil data bulan ini pertanggal (harian dalam bulan berjalan)
        $startOfMonth = Carbon::now()->startOfMonth()->startOfDay();
        $endOfMonth = Carbon::now()->endOfMonth()->endOfDay();

        // Siapkan array tanggal dalam bulan ini breakdown by type
        $daysInMonth = [];
        $period = \Carbon\CarbonPeriod::create($startOfMonth, $endOfMonth);
        foreach ($period as $date) {
            $daysInMonth[$date->toDateString()] = [
                'date' => $date->toDateString(),
                'topup' => 0,
                'booking' => 0,
                'membership' => 0,
            ];
        }

        // Query transaksi PAID per tanggal dan type di bulan ini
        $monthlyRaw = Transactions::selectRaw('DATE(created_at) as date, type, SUM(amount) as total')
            ->where('status', 'PAID')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get();

        foreach ($monthlyRaw as $row) {
            $date = $row->date;
            $type = strtoupper($row->type);
            if (isset($daysInMonth[$date])) {
                if ($type === 'TOPUP') {
                    $daysInMonth[$date]['topup'] = (float)$row->total;
                } elseif ($type === 'BOOKING') {
                    $daysInMonth[$date]['booking'] = (float)$row->total;
                } elseif ($type === 'MEMBERSHIP') {
                    $daysInMonth[$date]['membership'] = (float)$row->total;
                }
            }
        }

        // Re-index as numeric array
        $monthly = array_values($daysInMonth);

        $startOfYear = Carbon::now()->startOfYear()->startOfDay();
        $endOfYear = Carbon::now()->endOfYear()->endOfDay();
        $monthsInYear = [];
        for ($m = 1; $m <= 12; $m++) {
            $month = Carbon::create($startOfYear->year, $m, 1);
            $monthsInYear[$month->format('Y-m')] = [
                'date' => $month->format('Y-m'),
                'topup' => 0,
                'booking' => 0,
                'membership' => 0,
            ];
        }

        // Query transaksi PAID per bulan dan type di tahun ini
        $yearlyRaw = Transactions::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as date, type, SUM(amount) as total')
            ->where('status', 'PAID')
            ->whereBetween('created_at', [$startOfYear, $endOfYear])
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get();

        foreach ($yearlyRaw as $row) {
            $date = $row->date;
            $type = strtoupper($row->type);
            if (isset($monthsInYear[$date])) {
                if ($type === 'TOPUP') {
                    $monthsInYear[$date]['topup'] = (float)$row->total;
                } elseif ($type === 'BOOKING') {
                    $monthsInYear[$date]['booking'] = (float)$row->total;
                } elseif ($type === 'MEMBERSHIP') {
                    $monthsInYear[$date]['membership'] = (float)$row->total;
                }
            }
        }

        // Re-index as numeric array
        $yearly = array_values($monthsInYear);

        $data = [
            [
                'title' => 'Revenue',
                'number' => 'Rp ' . number_format($revenueToday, 0, ',', '.'),
                'class' => 'abu',
                'icon' => 'graph-up-arrow',
                'url' => '/all-transactions?type=PAID',
            ],
            [
                'title' => 'Pending Payment',
                'number' => $pendingPayment,
                'class' => 'warning',
                'icon' => 'clock',
                'url' => '/all-transactions?type=PENDING',
            ],
            [
                'title' => 'User Wallet Liability',
                'number' => 'Rp ' . number_format($userWalletLiability, 0, ',', '.'),
                'class' => 'biru',
                'icon' => 'wallet',
                'url' => '/wallet-ledger',
            ],
            [
                'title' => 'Active Booking',
                'number' => $activeBooking,
                'class' => 'success',
                'icon' => 'building-fill-check',
                'url' => '/booking-transactions?tab=1&status=CONFIRMED',
            ],
            [
                'title' => 'Active Membership',
                'number' => $activeMembership,
                'class' => 'primary',
                'icon' => 'person-fill-check',
                'url' => '/membership-transactions?tab=1&status=active',
            ],
            [
                'title' => 'Refund This Month',
                'number' => 'Rp ' . number_format($refundThisMonth, 0, ',', '.'),
                'class' => 'danger',
                'icon' => 'rewind-circle',
                'url' => '/all-transactions?type=REFUNDED',
            ],
        ];

        // Helper to flatten breakdown array to chart format
        $formatChart = function($arr) {
            $dates = array_map(function($item) { return $item['date']; }, $arr);
            $topup = array_map(function($item) { return $item['topup'] ?? 0; }, $arr);
            $booking = array_map(function($item) { return $item['booking'] ?? 0; }, $arr);
            $membership = array_map(function($item) { return $item['membership'] ?? 0; }, $arr);
            // If all arrays are empty, set default 0
            if (empty($dates)) {
                return [
                    'dates' => [],
                    'topup' => [0],
                    'booking' => [0],
                    'membership' => [0],
                ];
            }
            return [
                'dates' => $dates,
                'topup' => $topup,
                'booking' => $booking,
                'membership' => $membership,
            ];
        };

        $totalAmount = Transactions::where('status', 'PAID')->sum('amount');
        $bookingAmount = Transactions::where('status', 'PAID')->where('type', 'BOOKING')->sum('amount');
        $topupAmount = Transactions::where('status', 'PAID')->where('type', 'TOPUP')->sum('amount');
        $membershipAmount = Transactions::where('status', 'PAID')->where('type', 'MEMBERSHIP')->sum('amount');
        $composition = [
            'booking' => $totalAmount > 0 ? round($bookingAmount / $totalAmount * 100, 1) : 0,
            'topup' => $totalAmount > 0 ? round($topupAmount / $totalAmount * 100, 1) : 0,
            'membership' => $totalAmount > 0 ? round($membershipAmount / $totalAmount * 100, 1) : 0,
        ];

        // Live Transaction Feed (10 transaksi terakhir)
        $liveFeed = Transactions::with(['user:id,name'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($trx) {
                return [
                    'time' => $trx->created_at ? $trx->created_at->format('d M Y H:i') : '',
                    'user' => $trx->user ? $trx->user->name : '-',
                    'activity' => ucfirst($trx->type),
                    'amount' => (float)$trx->amount,
                    'status' => ucfirst($trx->status),
                ];
            });

            // Pending Topup Verification
        $pendingTopup = Transactions::where('status', 'PENDING')->where('type', 'TOPUP')->count();
        // Booking Unpaid > 1 Hour
        $bookingUnpaid = Transactions::where('status', 'PENDING')
            ->where('type', 'BOOKING')
            ->where('created_at', '<', Carbon::now()->subHour())
            ->count();
        // Failed Payment
        $failedPayment = Transactions::where('status', 'FAILED')->count();

        $needAttention = [
            'pending_topup' => $pendingTopup,
            'unpaid_booking' => $bookingUnpaid,
            'failed_payment' => $failedPayment,
        ];

         // SECTION 6 — Property Performance Snapshot
        $propertyPerformancePaginator = Booking::selectRaw('properties.properties as property, COUNT(*) as booking_today, SUM(grand_total) as revenue')
            ->leftJoin('properties', 'bookings.property_id', '=', 'properties.id')
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->groupBy('properties.properties')
            ->orderByDesc('booking_today')
            ->paginate(10);

        $propertyPerformance = $propertyPerformancePaginator->setCollection(
            $propertyPerformancePaginator->getCollection()->map(function($row) {
                return [
                    'property' => $row->property,
                    'booking_today' => (int)$row->booking_today,
                    'revenue' => (float)$row->revenue,
                ];
            })
        );

        $response = [
            'data' => $data,
            'transaction_chart' => [
                'daily' => $formatChart($daily),
                'weekly' => $formatChart($weekly),
                'monthly' => $formatChart($monthly),
                'yearly' => $formatChart($yearly),
            ],
            'transaction_composition' => $composition,
            'live_transaction_feed' => $liveFeed,
            'need_attention' => $needAttention,
            'property_performance' => $propertyPerformance,
        ];
        return $response;
    }
}
