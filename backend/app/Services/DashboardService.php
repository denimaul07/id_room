<?php
namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Transactions;
use App\Models\User;
use App\Models\Membership;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\UserMembership;
use App\Models\Rooms;
use App\Models\Crm;
use App\Models\Properties;
use App\Models\RoomSub;
use App\Models\Parameter;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

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


        // 👛 User Wallet Liability: SUM(users.balance)
        $userWalletLiability = User::where('status_users', 0)->sum('balance');

        // 🏨 Active Booking: COUNT(status=CONFIRMED)
        $activeBooking = Booking::where('status', 'CONFIRMED')->where('type_booking', 'ONLINE')->count();

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
                'title' => 'User Wallet Liability',
                'number' => 'Rp ' . number_format($userWalletLiability, 0, ',', '.'),
                'class' => 'biru',
                'icon' => 'wallet',
                'url' => '/wallet-ledger',
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
        $pendingPayment = Transactions::where('status', 'PENDING')->count();
        // Booking Unpaid > 1 Hour
        $bookingUnpaid = Transactions::where('status', 'PENDING')
            ->where('type', 'BOOKING')
            ->where('created_at', '<', Carbon::now()->subHour())
            ->count();
        // Failed Payment
        $failedPayment = Transactions::where('status', 'FAILED')->count();

        $needAttention = [
            'pending_payment' => $pendingPayment,
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

        //Booking

        $revueBooking = Transactions::whereBetween('created_at', [$startDate, $endDate])
        ->where('type', 'BOOKING')
        ->where('status', 'PAID')
        ->sum('amount');

        $totalBookingToday = Booking::whereBetween('created_at', [$startDate, $endDate])->where('type_booking', 'ONLINE')->count();

        $checkInToday = Booking::whereDate('check_in', Carbon::today())->where('type_booking', 'ONLINE')->count();

        $checkOutToday = Booking::whereDate('check_out', Carbon::today())->where('type_booking', 'ONLINE')->count();

        $completedBooking = Booking::where('status', 'CONFIRMED')
        ->where('checkin', 'Y')
        ->where('type_booking', 'ONLINE')
        ->whereBetween('checkout_date', [$startDate, $endDate])
        ->count();

        $activeStay = Booking::where('checkin', 'Y')
        ->where('type_booking', 'ONLINE')
        ->where('checkin_date', '<=', $endDate)
        ->where('checkout_date', '>=', $startDate)
        ->count();

        $cancelledBooking = Booking::where('status', 'CANCELLED')
        ->where('type_booking', 'ONLINE')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();

        // Calculate occupancy rate (example: total active stays divided by total bookings, avoid division by zero)
        $occupancyRate = $totalBookingToday > 0 ? round(($activeStay / $totalBookingToday) * 100, 2) : 0;

        $dataBooking = [
            'total_booking_today' => $totalBookingToday,
            'check_in_today' => $checkInToday,
            'check_out_today' => $checkOutToday,
            'active_stay' => $activeStay,
            'occupancy_rate' => $occupancyRate,
            'revenue' => 'Rp ' . number_format($revueBooking, 0, ',', '.'),
            'completed_booking' => $completedBooking,
            'cancelled_booking' => $cancelledBooking,
        ];

        // Helper to flatten breakdown array to chart format
        $formatChartBooking = function($arr) {
            $dates = array_map(function($item) { return $item['date']; }, $arr);
            $pending = array_map(function($item) { return $item['pending'] ?? 0; }, $arr);
            $paid = array_map(function($item) { return $item['paid'] ?? 0; }, $arr);
            $cancelled = array_map(function($item) { return $item['cancelled'] ?? 0; }, $arr);
            $expired = array_map(function($item) { return $item['expired'] ?? 0; }, $arr);
            $completed = array_map(function($item) { return $item['completed'] ?? 0; }, $arr);
            $blocked = array_map(function($item) { return $item['blocked'] ?? 0; }, $arr);
            if (empty($dates)) {
                return [
                    'dates' => [],
                    'pending' => [0],
                    'paid' => [0],
                    'cancelled' => [0],
                    'expired' => [0],
                    'completed' => [0],
                    'blocked' => [0],
                ];
            }

            return [
                'dates' => $dates,
                'pending' => $pending,
                'paid' => $paid,
                'cancelled' => $cancelled,
                'expired' => $expired,
                'completed' => $completed,
                'blocked' => $blocked,
            ];
        };

        // Get daily booking status breakdown
        $dailyRawBooking = Booking::selectRaw('DATE(created_at) as date, status, COUNT(*) as total')
            ->whereBetween('created_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])
            ->where('type_booking', 'ONLINE')
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get();

        $dailyBooking = [];
        foreach ($dailyRawBooking as $row) {
            $date = $row->date;
            $status = strtoupper($row->status);
            if (!isset($dailyBooking[$date])) {
                $dailyBooking[$date] = [
                    'date' => $date,
                    'pending' => 0,
                    'paid' => 0,
                    'cancelled' => 0,
                    'expired' => 0,
                    'completed' => 0,
                    'blocked' => 0,
                ];
            }
            if ($status === 'PENDING') {
                $dailyBooking[$date]['pending'] = (int)$row->total;
            } elseif ($status === 'PAID') {
                $dailyBooking[$date]['paid'] = (int)$row->total;
            } elseif ($status === 'CANCELLED') {
                $dailyBooking[$date]['cancelled'] = (int)$row->total;
            } elseif ($status === 'EXPIRED') {
                $dailyBooking[$date]['expired'] = (int)$row->total;
            } elseif ($status === 'COMPLETED') {
                $dailyBooking[$date]['completed'] = (int)$row->total;
            } elseif ($status === 'BLOCKED') {
                $dailyBooking[$date]['blocked'] = (int)$row->total;
            }
        }

        usort($dailyBooking, function($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });
        $dailyBooking = array_values($dailyBooking);



        // Ambil data 7 hari terakhir breakdown by type
        // Ambil data 7 hari terakhir breakdown by status
        $last7DaysBooking = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $last7DaysBooking[$date] = [
                'date' => $date,
                'pending' => 0,
                'paid' => 0,
                'cancelled' => 0,
                'expired' => 0,
                'completed' => 0,
                'blocked' => 0,
            ];
        }

        $weeklyRawBooking = Booking::selectRaw('DATE(created_at) as date, status, COUNT(*) as total')
            ->whereBetween('created_at', [Carbon::today()->subDays(6)->startOfDay(), Carbon::today()->endOfDay()])
            ->where('type_booking', 'ONLINE')
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get();

        foreach ($weeklyRawBooking as $row) {
            $date = $row->date;
            $status = strtoupper($row->status);
            if (isset($last7DaysBooking[$date])) {
            if ($status === 'PENDING') {
                $last7DaysBooking[$date]['pending'] = (int)$row->total;
            } elseif ($status === 'PAID') {
                $last7DaysBooking[$date]['paid'] = (int)$row->total;
            } elseif ($status === 'CANCELLED') {
                $last7DaysBooking[$date]['cancelled'] = (int)$row->total;
            } elseif ($status === 'EXPIRED') {
                $last7DaysBooking[$date]['expired'] = (int)$row->total;
            } elseif ($status === 'COMPLETED') {
                $last7DaysBooking[$date]['completed'] = (int)$row->total;
            } elseif ($status === 'BLOCKED') {
                $last7DaysBooking[$date]['blocked'] = (int)$row->total;
            }
            }
        }

        // Re-index as numeric array
        $weeklyBooking  = array_values($last7DaysBooking);

        // Ambil data bulan ini pertanggal (harian dalam bulan berjalan)
        $startOfMonth = Carbon::now()->startOfMonth()->startOfDay();
        $endOfMonth = Carbon::now()->endOfMonth()->endOfDay();

        // Siapkan array tanggal dalam bulan ini breakdown by status
        $daysInMonthBooking  = [];
        $periodBooking  = CarbonPeriod::create($startOfMonth, $endOfMonth);
        foreach ($periodBooking as $date) {
            $daysInMonthBooking[$date->toDateString()] = [
                'date' => $date->toDateString(),
                'pending' => 0,
                'paid' => 0,
                'cancelled' => 0,
                'expired' => 0,
                'completed' => 0,
                'blocked' => 0,
            ];
        }

        // Query booking per tanggal dan status di bulan ini
        $monthlyRawBooking  = Booking::selectRaw('DATE(created_at) as date, status, COUNT(*) as total')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('type_booking', 'ONLINE')
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get();

        foreach ($monthlyRawBooking as $row) {
            $date = $row->date;
            $status = strtoupper($row->status);
            if (isset($daysInMonthBooking[$date])) {
                if ($status === 'PENDING') {
                    $daysInMonthBooking[$date]['pending'] = (int)$row->total;
                } elseif ($status === 'PAID') {
                    $daysInMonthBooking[$date]['paid'] = (int)$row->total;
                } elseif ($status === 'CANCELLED') {
                    $daysInMonthBooking[$date]['cancelled'] = (int)$row->total;
                } elseif ($status === 'EXPIRED') {
                    $daysInMonthBooking[$date]['expired'] = (int)$row->total;
                } elseif ($status === 'COMPLETED') {
                    $daysInMonthBooking[$date]['completed'] = (int)$row->total;
                } elseif ($status === 'BLOCKED') {
                    $daysInMonthBooking[$date]['blocked'] = (int)$row->total;
                }
            }
        }

        // Re-index as numeric array
        $monthly = array_values($daysInMonthBooking);

        $startOfYearBooking  = Carbon::now()->startOfYear()->startOfDay();
        $endOfYearBooking = Carbon::now()->endOfYear()->endOfDay();
        $monthsInYearBooking  = [];
        for ($m = 1; $m <= 12; $m++) {
            $month = Carbon::create($startOfYearBooking->year, $m, 1);
            $monthsInYearBooking[$month->format('Y-m')] = [
            'date' => $month->format('Y-m'),
            'pending' => 0,
            'paid' => 0,
            'cancelled' => 0,
            'expired' => 0,
            'completed' => 0,
            'blocked' => 0,
            ];
        }

        // Query booking per bulan dan status di tahun ini
        $yearlyRawBooking  = Booking::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as date, status, COUNT(*) as total')
            ->whereBetween('created_at', [$startOfYearBooking, $endOfYearBooking])
            ->where('type_booking', 'ONLINE')
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get();

        foreach ($yearlyRawBooking as $row) {
            $date = $row->date;
            $status = strtoupper($row->status);
            if (isset($monthsInYearBooking[$date])) {
            if ($status === 'PENDING') {
                $monthsInYearBooking[$date]['pending'] = (int)$row->total;
            } elseif ($status === 'PAID') {
                $monthsInYearBooking[$date]['paid'] = (int)$row->total;
            } elseif ($status === 'CANCELLED') {
                $monthsInYearBooking[$date]['cancelled'] = (int)$row->total;
            } elseif ($status === 'EXPIRED') {
                $monthsInYearBooking[$date]['expired'] = (int)$row->total;
            } elseif ($status === 'COMPLETED') {
                $monthsInYearBooking[$date]['completed'] = (int)$row->total;
            } elseif ($status === 'BLOCKED') {
                $monthsInYearBooking[$date]['blocked'] = (int)$row->total;
            }
            }
        }

        // Re-index as numeric array
        $yearlyBooking  = array_values($monthsInYearBooking);

        // Membership Status Trend breakdown (daily, weekly, monthly, yearly)

        $formatChartMembership = function($arr) {
            $dates = array_map(function($item) { return $item['date']; }, $arr);
            $pending = array_map(function($item) { return $item['pending'] ?? 0; }, $arr);
            $active = array_map(function($item) { return $item['active'] ?? 0; }, $arr);
            $expired = array_map(function($item) { return $item['expired'] ?? 0; }, $arr);
            $cancelled = array_map(function($item) { return $item['cancelled'] ?? 0; }, $arr);
            if (empty($dates)) {
                return [
                    'dates' => [],
                    'pending' => [0],
                    'active' => [0],
                    'expired' => [0],
                    'cancelled' => [0],
                ];
            }
            return [
                'dates' => $dates,
                'pending' => $pending,
                'active' => $active,
                'expired' => $expired,
                'cancelled' => $cancelled,
            ];
        };

        
        $membershipDailyRaw = UserMembership::selectRaw('DATE(created_at) as date, status, COUNT(*) as total')
        ->whereBetween('created_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])
        ->groupBy('date', 'status')
        ->orderBy('date')
        ->get();

        $membershipDaily = [];
        foreach ($membershipDailyRaw as $row) {
            $date = $row->date;
            $status = strtoupper($row->status);
            if (!isset($membershipDaily[$date])) {
                $membershipDaily[$date] = [
                    'date' => $date,
                    'pending' => 0,
                    'active' => 0,
                    'expired' => 0,
                    'cancelled' => 0,
                ];
            }
            if ($status === 'PENDING') {
                $membershipDaily[$date]['pending'] = (int)$row->total;
            } elseif ($status === 'ACTIVE') {
                $membershipDaily[$date]['active'] = (int)$row->total;
            } elseif ($status === 'EXPIRED') {
                $membershipDaily[$date]['expired'] = (int)$row->total;
            } elseif ($status === 'CANCELLED') {
                $membershipDaily[$date]['cancelled'] = (int)$row->total;
            }
        }

        $membershipDaily = array_values($membershipDaily);
        $membershipDaily = $formatChartMembership($membershipDaily);

        $last7DaysMembership = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $last7DaysMembership[$date] = [
                'date' => $date,
                'pending' => 0,
                'active' => 0,
                'expired' => 0,
                'cancelled' => 0,
            ];
        }

        $membershipWeeklyRaw = UserMembership::selectRaw('DATE(created_at) as date, status, COUNT(*) as total')
        ->whereBetween('created_at', [Carbon::today()->subDays(6)->startOfDay(), Carbon::today()->endOfDay()])
        ->groupBy('date', 'status')
        ->orderBy('date')
        ->get();

        foreach ($membershipWeeklyRaw as $row) {
            $date = $row->date;
            $status = strtoupper($row->status);
            if (isset($last7DaysMembership[$date])) {
                if ($status === 'PENDING') {
                    $last7DaysMembership[$date]['pending'] = (int)$row->total;
                } elseif ($status === 'ACTIVE') {
                    $last7DaysMembership[$date]['active'] = (int)$row->total;
                } elseif ($status === 'EXPIRED') {
                    $last7DaysMembership[$date]['expired'] = (int)$row->total;
                } elseif ($status === 'CANCELLED') {
                    $last7DaysMembership[$date]['cancelled'] = (int)$row->total;
                }
            }
        }

        $membershipWeekly = array_values($last7DaysMembership);
        $membershipWeekly = $formatChartMembership($membershipWeekly);

          // Siapkan array tanggal dalam bulan ini breakdown by status
        $daysInMonthMembership = [];
        $periodMembership  = CarbonPeriod::create($startOfMonth, $endOfMonth);
        foreach ($periodMembership as $date) {
            $daysInMonthMembership[$date->toDateString()] = [
                'date' => $date->toDateString(),
                'pending' => 0,
                'paid' => 0,
                'cancelled' => 0,
                'expired' => 0,
                'completed' => 0,
                'blocked' => 0,
            ];
        }

        $membershipMonthlyRaw = UserMembership::selectRaw('DATE(created_at) as date, status, COUNT(*) as total')
        ->whereBetween('created_at', [Carbon::now()->startOfMonth()->startOfDay(), Carbon::now()->endOfMonth()->endOfDay()])
        ->groupBy('date', 'status')
        ->orderBy('date')
        ->get();

        $membershipMonthly = [];
        foreach ($membershipMonthlyRaw as $row) {
            $date = $row->date;
            $status = strtoupper($row->status);
            if (!isset($daysInMonthMembership[$date])) {
                $daysInMonthMembership[$date] = [
                    'date' => $date,
                    'pending' => 0,
                    'active' => 0,
                    'expired' => 0,
                    'cancelled' => 0,
                ];
            }
            if ($status === 'PENDING') {
                $daysInMonthMembership[$date]['pending'] = (int)$row->total;
            } elseif ($status === 'ACTIVE') {
                $daysInMonthMembership[$date]['active'] = (int)$row->total;
            } elseif ($status === 'EXPIRED') {
                $daysInMonthMembership[$date]['expired'] = (int)$row->total;
            } elseif ($status === 'CANCELLED') {
                $daysInMonthMembership[$date]['cancelled'] = (int)$row->total;
            }
        }

        $membershipMonthly = array_values($daysInMonthMembership);
        $membershipMonthly = $formatChartMembership($membershipMonthly);



        $startOfYearMembership = Carbon::now()->startOfYear()->startOfDay();
        $endOfYearMembership = Carbon::now()->endOfYear()->endOfDay();
        $monthsInYearMembership  = [];
        for ($m = 1; $m <= 12; $m++) {
            $month = Carbon::create($startOfYearMembership->year, $m, 1);
            $monthsInYearMembership[$month->format('Y-m')] = [
            'date' => $month->format('Y-m'),
            'pending' => 0,
            'paid' => 0,
            'cancelled' => 0,
            'expired' => 0,
            'completed' => 0,
            'blocked' => 0,
            ];
        }


        $membershipYearlyRaw = UserMembership::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as date, status, COUNT(*) as total')
        ->whereBetween('created_at', [$startOfYearMembership, $endOfYearMembership])
        ->groupBy('date', 'status')
        ->orderBy('date')
        ->get();

        $membershipYearly = [];
        foreach ($membershipYearlyRaw as $row) {
            $date = $row->date;
            $status = strtoupper($row->status);
            if (!isset($monthsInYearMembership[$date])) {
                $monthsInYearMembership[$date] = [
                    'date' => $date,
                    'pending' => 0,
                    'active' => 0,
                    'expired' => 0,
                    'cancelled' => 0,
                ];
            }
            if ($status === 'PENDING') {
                $monthsInYearMembership[$date]['pending'] = (int)$row->total;
            } elseif ($status === 'ACTIVE') {
                $monthsInYearMembership[$date]['active'] = (int)$row->total;
            } elseif ($status === 'EXPIRED') {
                $monthsInYearMembership[$date]['expired'] = (int)$row->total;
            } elseif ($status === 'CANCELLED') {
                $monthsInYearMembership[$date]['cancelled'] = (int)$row->total;
            }
        }
        $membershipYearly = array_values($monthsInYearMembership);
        $membershipYearly = $formatChartMembership($membershipYearly);


        $revueMembership = Transactions::whereBetween('created_at', [$startDate, $endDate])
        ->where('type', 'MEMBERSHIP')
        ->where('status', 'PAID')
        ->sum('amount');

        $totalMembership = UserMembership::count();
        $totalPendingMembership = UserMembership::where('status', 'PENDING')->count();
        $totalActiveMembership = UserMembership::where('status', 'ACTIVE')->count();
        $totalExpiredMembership = UserMembership::where('status', 'EXPIRED')->count();
        $totalCancelledMembership = UserMembership::where('status', 'CANCELLED')->count();

        $dataMembership = [
            'revenue' => 'Rp ' . number_format($revueMembership, 0, ',', '.'),
            'total_membership' => $totalMembership,
            'total_pending' => $totalPendingMembership,
            'total_active' => $totalActiveMembership,
            'total_expired' => $totalExpiredMembership,
            'total_cancelled' => $totalCancelledMembership,
        ];

        //CRM data
        // CRM Data
        $totalCrm = Crm::whereBetween('tanggal_leads', [$startDate, $endDate])->count();
        $totalCrmNeedfu = Crm::where('status', 'NEEDFU')->whereBetween('tanggal_leads', [$startDate, $endDate])->count();
        $totalCrmFollowup = Crm::where('status', 'FOLLOWUP')->whereBetween('tanggal_leads', [$startDate, $endDate])->count();
        $totalCrmLost = Crm::where('status', 'LOST')->whereBetween('tanggal_leads', [$startDate, $endDate])->count();
        $totalCrmClosing = Crm::where('status', 'CLOSING')->whereBetween('tanggal_leads', [$startDate, $endDate])->count();

        $dataCrm = [
            'total_crm' => $totalCrm,
            'total_needfu' => $totalCrmNeedfu,
            'total_followup' => $totalCrmFollowup,
            'total_lost' => $totalCrmLost,
            'total_closing' => $totalCrmClosing,
        ];

        // CRM Status Trend (daily, weekly, monthly, yearly)
        $formatChartCrm = function($arr) {
            $dates = array_map(function($item) { return $item['date']; }, $arr);
            $needfu = array_map(function($item) { return $item['needfu'] ?? 0; }, $arr);
            $followup = array_map(function($item) { return $item['followup'] ?? 0; }, $arr);
            $lost = array_map(function($item) { return $item['lost'] ?? 0; }, $arr);
            $closing = array_map(function($item) { return $item['closing'] ?? 0; }, $arr);
            if (empty($dates)) {
                return [
                    'dates' => [],
                    'needfu' => [0],
                    'followup' => [0],
                    'lost' => [0],
                    'closing' => [0],
                ];
            }
            return [
                'dates' => $dates,
                'needfu' => $needfu,
                'followup' => $followup,
                'lost' => $lost,
                'closing' => $closing,
            ];
        };

        $crmDailyStatus = [];
        $crmDailyRaw = Crm::selectRaw('DATE(tanggal_leads) as date, status, COUNT(*) as total')
            ->whereBetween('tanggal_leads', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get();
        foreach ($crmDailyRaw as $row) {
            $date = $row->date;
            $status = strtoupper($row->status);
            if (!isset($crmDailyStatus[$date])) {
                $crmDailyStatus[$date] = [
                    'date' => $date,
                    'needfu' => 0,
                    'followup' => 0,
                    'lost' => 0,
                    'closing' => 0,
                ];
            }
            if ($status === 'NEEDFU') {
                $crmDailyStatus[$date]['needfu'] = (int)$row->total;
            } elseif ($status === 'FOLLOWUP') {
                $crmDailyStatus[$date]['followup'] = (int)$row->total;
            } elseif ($status === 'LOST') {
                $crmDailyStatus[$date]['lost'] = (int)$row->total;
            } elseif ($status === 'CLOSING') {
                $crmDailyStatus[$date]['closing'] = (int)$row->total;
            }
        }
        $crmDailyStatus = array_values($crmDailyStatus);
        
        $crmWeeklyStatus = [];
        $crmWeeklyRaw = Crm::selectRaw('DATE(tanggal_leads) as date, status, COUNT(*) as total')
            ->whereBetween('tanggal_leads', [Carbon::today()->subDays(6)->startOfDay(), Carbon::today()->endOfDay()])
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $crmWeeklyStatus[$date] = [
                'date' => $date,
                'needfu' => 0,
                'followup' => 0,
                'lost' => 0,
                'closing' => 0,
            ];
        }
        foreach ($crmWeeklyRaw as $row) {
            $date = $row->date;
            $status = strtoupper($row->status);
            if (isset($crmWeeklyStatus[$date])) {
                if ($status === 'NEEDFU') {
                    $crmWeeklyStatus[$date]['needfu'] = (int)$row->total;
                } elseif ($status === 'FOLLOWUP') {
                    $crmWeeklyStatus[$date]['followup'] = (int)$row->total;
                } elseif ($status === 'LOST') {
                    $crmWeeklyStatus[$date]['lost'] = (int)$row->total;
                } elseif ($status === 'CLOSING') {
                    $crmWeeklyStatus[$date]['closing'] = (int)$row->total;
                }
            }
        }
        $crmWeeklyStatus = array_values($crmWeeklyStatus);

        $startOfMonthCrm = Carbon::now()->startOfMonth()->startOfDay();
        $endOfMonthCrm = Carbon::now()->endOfMonth()->endOfDay();
        $crmMonthlyStatus = [];
        $periodCrm = CarbonPeriod::create($startOfMonthCrm, $endOfMonthCrm);
        foreach ($periodCrm as $date) {
            $crmMonthlyStatus[$date->toDateString()] = [
                'date' => $date->toDateString(),
                'needfu' => 0,
                'followup' => 0,
                'lost' => 0,
                'closing' => 0,
            ];
        }
        $crmMonthlyRaw = Crm::selectRaw('DATE(tanggal_leads) as date, status, COUNT(*) as total')
            ->whereBetween('tanggal_leads', [$startOfMonthCrm, $endOfMonthCrm])
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get();
        foreach ($crmMonthlyRaw as $row) {
            $date = $row->date;
            $status = strtoupper($row->status);
            if (isset($crmMonthlyStatus[$date])) {
                if ($status === 'NEEDFU') {
                    $crmMonthlyStatus[$date]['needfu'] = (int)$row->total;
                } elseif ($status === 'FOLLOWUP') {
                    $crmMonthlyStatus[$date]['followup'] = (int)$row->total;
                } elseif ($status === 'LOST') {
                    $crmMonthlyStatus[$date]['lost'] = (int)$row->total;
                } elseif ($status === 'CLOSING') {
                    $crmMonthlyStatus[$date]['closing'] = (int)$row->total;
                }
            }
        }
        $crmMonthlyStatus = array_values($crmMonthlyStatus);

        $startOfYearCrm = Carbon::now()->startOfYear()->startOfDay();
        $endOfYearCrm = Carbon::now()->endOfYear()->endOfDay();
        $crmYearlyStatus = [];
        for ($m = 1; $m <= 12; $m++) {
            $month = Carbon::create($startOfYearCrm->year, $m, 1);
            $crmYearlyStatus[$month->format('Y-m')] = [
                'date' => $month->format('Y-m'),
                'needfu' => 0,
                'followup' => 0,
                'lost' => 0,
                'closing' => 0,
            ];
        }
        $crmYearlyRaw = Crm::selectRaw('DATE_FORMAT(tanggal_leads, "%Y-%m") as date, status, COUNT(*) as total')
            ->whereBetween('tanggal_leads', [$startOfYearCrm, $endOfYearCrm])
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get();
        foreach ($crmYearlyRaw as $row) {
            $date = $row->date;
            $status = strtoupper($row->status);
            if (isset($crmYearlyStatus[$date])) {
                if ($status === 'NEEDFU') {
                    $crmYearlyStatus[$date]['needfu'] = (int)$row->total;
                } elseif ($status === 'FOLLOWUP') {
                    $crmYearlyStatus[$date]['followup'] = (int)$row->total;
                } elseif ($status === 'LOST') {
                    $crmYearlyStatus[$date]['lost'] = (int)$row->total;
                } elseif ($status === 'CLOSING') {
                    $crmYearlyStatus[$date]['closing'] = (int)$row->total;
                }
            }
        }
        $crmYearlyStatus = array_values($crmYearlyStatus);

        // User Achievement: Count CRM by assigned_id and status
        $userAchievements = Crm::selectRaw('assigned_id, status, COUNT(*) as total')
            ->with('assigned:id,name') // Eager load user data
            ->whereBetween('tanggal_leads', [$startDate, $endDate])
            ->groupBy('assigned_id', 'status')
            ->get()
            ->groupBy('assigned_id')
            ->map(function ($items, $assignedId) {
            $user = $items->first()->assigned ?? null;
            $result = [
                'assigned_name' => $user ? $user->name : '-',
                'needfu' => 0,
                'followup' => 0,
                'lost' => 0,
                'closing' => 0,
            ];
            foreach ($items as $item) {
                $status = strtolower($item->status);
                if (isset($result[$status])) {
                $result[$status] = (int)$item->total;
                }
            }
            return $result;
            })
            ->values();
        

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
            'dataBooking' => $dataBooking,
            'booking_status_trend' => [
                'daily' => $formatChartBooking($dailyBooking),
                'weekly' => $formatChartBooking($weeklyBooking),
                'monthly' => $formatChartBooking($monthly),
                'yearly' => $formatChartBooking($yearlyBooking),
            ],
            'membership_status_trend' => [
                'daily' => $membershipDaily,
                'weekly' => $membershipWeekly,
                'monthly' => $membershipMonthly,
                'yearly' => $membershipYearly,
            ],
            'dataMembership' => $dataMembership,
            'crm_status_trend' => [
                'daily' => $formatChartCrm($crmDailyStatus),
                'weekly' => $formatChartCrm($crmWeeklyStatus),
                'monthly' => $formatChartCrm($crmMonthlyStatus),
                'yearly' => $formatChartCrm($crmYearlyStatus),
            ],
            'dataCrm' => $dataCrm,
            'user_achievement' => $userAchievements,
        ];

        return $response;
    }



    public function getBookingDetail($bookingId)
    {
        $booking = BookingPayment::with(['booking','booking.user','booking.room','booking.property','booking.passengers'])->find($bookingId);
        if (!$booking) {
            return null;
        }
        return $booking;
    }

    public function getBookingAvailability($property)
    {
        $calendarDays = [];
        $calendarStart = Carbon::today();
        $calendarEnd = Carbon::today()->addDays(30);

        for ($d = $calendarStart->copy(); $d->lte($calendarEnd); $d->addDay()) {
            $calendarDays[] = $d->toDateString();
        }

        // Ambil rooms
        $rooms = Rooms::with('subRooms')->when($property, function ($query) use ($property) {
            $query->where('property_odata', $property);
        })->get();

        $roomOdataList = $rooms->pluck('odata')->toArray();

        $subRoomOdataToRoomOdata = [];
        $subRoomIdToRoomOdata = [];
        $subRoomMetaByOdata = [];
        $subRoomMetaById = [];

        foreach ($rooms as $room) {
            foreach ($room->subRooms as $subRoom) {
                if (!empty($subRoom->odata)) {
                    $subRoomOdataToRoomOdata[$subRoom->odata] = $room->odata;
                    $subRoomMetaByOdata[$subRoom->odata] = [
                        'odata' => $subRoom->odata,
                        'code_room' => $subRoom->code_room,
                        'name_room' => $subRoom->name_room,
                    ];
                }
                if (!empty($subRoom->id)) {
                    $subRoomIdToRoomOdata[$subRoom->id] = $room->odata;
                    $subRoomMetaById[$subRoom->id] = [
                        'odata' => $subRoom->odata,
                        'code_room' => $subRoom->code_room,
                        'name_room' => $subRoom->name_room,
                    ];
                }
            }
        }

        $subRoomOdataList = array_keys($subRoomOdataToRoomOdata);
        $subRoomIdList = array_keys($subRoomIdToRoomOdata);

        $bookings = Booking::query()
            ->where(function ($query) use ($subRoomOdataList, $subRoomIdList, $roomOdataList) {
                if (!empty($subRoomOdataList)) {
                    $query->whereIn('room_sub_odata', $subRoomOdataList);
                }

                if (!empty($subRoomIdList)) {
                    $query->orWhereIn('room_sub_id', $subRoomIdList);
                }

                if (!empty($roomOdataList)) {
                    $query->orWhereIn('room_odata', $roomOdataList);
                }
            })
            ->whereIn('status', ['PENDING', 'PAID', 'CONFIRMED', 'BLOCKED', 'PREPARED'])
            ->where(function ($q) use ($calendarStart, $calendarEnd) {
                $q->where('checkin_date', '<', $calendarEnd->toDateString())
                ->where('checkout_date', '>', $calendarStart->toDateString());
            })
            ->with('user')
            ->get();

        // Index booking
        $bookingMap = [];

        foreach ($bookings as $booking) {
            $targetRoomOdata = null;

            if (!empty($booking->room_sub_odata) && isset($subRoomOdataToRoomOdata[$booking->room_sub_odata])) {
                $targetRoomOdata = $subRoomOdataToRoomOdata[$booking->room_sub_odata];
            } elseif (!empty($booking->room_sub_id) && isset($subRoomIdToRoomOdata[$booking->room_sub_id])) {
                $targetRoomOdata = $subRoomIdToRoomOdata[$booking->room_sub_id];
            } elseif (!empty($booking->room_odata)) {
                $targetRoomOdata = $booking->room_odata;
            }

            if (!$targetRoomOdata) {
                continue;
            }

            $period = CarbonPeriod::create(
                $booking->checkin_date,
                Carbon::parse($booking->checkout_date)->subDay()
            );

            foreach ($period as $date) {
                $dateStr = $date->toDateString();
                $bookingMap[$targetRoomOdata][$dateStr] = $booking;
            }
        }

        // Build calendar
        $roomCalendar = [];

        foreach ($rooms as $room) {

            $row = [
                'room_id' => $room->odata,
                'room_name' => $room->room_name,
                'sub_rooms' => $room->subRooms->map(function ($subRoom) {
                    return [
                        'odata' => $subRoom->odata,
                        'code_room' => $subRoom->code_room,
                        'name_room' => $subRoom->name_room,
                    ];
                })->values()->toArray(),
                'calendar' => []
            ];

            foreach ($calendarDays as $date) {

                $cell = [
                    'date' => $date,
                    'status' => 'available',
                    'booking_user' => null,
                    'booking_odata' => null,
                    'sub_room_odata' => null,
                    'sub_room_code' => null,
                    'sub_room_name' => null,
                    'can_block' => true,
                    'can_open' => false,
                    'type' => null,
                ];

                if (isset($bookingMap[$room->odata][$date])) {
                    $booking = $bookingMap[$room->odata][$date];

                    if ($booking->status === 'BLOCKED') {
                        $cell['status'] = 'blocked';
                    } elseif ($booking->status === 'PREPARED') {
                        $cell['status'] = 'prepared';
                    } else {
                        $cell['status'] = 'booked';
                    }
                    $cell['booking_odata'] = $booking->odata;
                    $cell['booking_user'] = optional($booking->user)->name;

                    $subRoomMeta = null;
                    if (!empty($booking->room_sub_odata) && isset($subRoomMetaByOdata[$booking->room_sub_odata])) {
                        $subRoomMeta = $subRoomMetaByOdata[$booking->room_sub_odata];
                    } elseif (!empty($booking->room_sub_id) && isset($subRoomMetaById[$booking->room_sub_id])) {
                        $subRoomMeta = $subRoomMetaById[$booking->room_sub_id];
                    }

                    if ($subRoomMeta) {
                        $cell['sub_room_odata'] = $subRoomMeta['odata'];
                        $cell['sub_room_code'] = $subRoomMeta['code_room'];
                        $cell['sub_room_name'] = $subRoomMeta['name_room'];
                    }

                    $cell['can_block'] = false;
                    $cell['can_open'] = in_array($booking->status, ['BLOCKED', 'PREPARED']);
                    $cell['type'] = $booking->type_booking ?? null;
                }

                $row['calendar'][] = $cell;
            }

            $roomCalendar[] = $row;
        }

        $response['room_availability_calendar'] = [
            'days' => $calendarDays,
            'rooms' => $roomCalendar,
        ];

        return $response;
    }

    //properties

    public function getOverviewProperties($selectedFilter, $customStart, $customEnd)
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

        $today = Carbon::today()->toDateString();
        // Ambil kode property dari user yang login
        $userKode = auth()->user()->kode ?? null;

        // Today Check-in
        $todayCheckIn = Booking::whereDate('check_in', $today)
            ->where('status', 'CONFIRMED')
            ->where('property_odata', $userKode)
            ->count();

        // Today Check-out
        $todayCheckOut = Booking::whereDate('check_out', $today)
            ->where('status', 'CONFIRMED')
            ->where('property_odata', $userKode)
            ->count();

        // Room Occupied (rooms with active stay today)
        $roomOccupied = Booking::where('checkin', 'Y')
            ->where('checkin_date', '<=', $endDate)
            ->where('checkout_date', '>=', $startDate)
            ->where('property_odata', $userKode)
            ->count();

  
        // Pending Booking (bookings with status PENDING in date range)
        $pendingBooking = Booking::where('status', 'PENDING')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('property_odata', $userKode)
            ->count();

        // Revenue Today (sum of PAID booking transactions in date range)
        $revenueToday = Transactions::where('status', 'PAID')
        ->where('type', 'BOOKING')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->whereHas('bookingPayments.booking', function($q) use ($userKode) {
            $q->where('property_odata', $userKode);
        })
        ->sum('amount');


        

        $data = [
            [
            'title' => 'Today Check-in',
            'number' => $todayCheckIn,
            'class' => 'success',
            'icon' => 'calendar-check',
            'url' => '/bookings?filter=today_checkin',
            ],
            [
            'title' => 'Today Check-out',
            'number' => $todayCheckOut,
            'class' => 'warning',
            'icon' => 'calendar-x',
            'url' => '/bookings?filter=today_checkout',
            ],
            [
            'title' => 'Kamar Terisi',
            'number' => $roomOccupied,
            'class' => 'hijau',
            'icon' => 'door-closed',
            'url' => '/rooms?filter=occupied',
            ],
            [
            'title' => 'Pending Booking',
            'number' => $pendingBooking,
            'class' => 'danger',
            'icon' => 'clock',
            'url' => '/bookings?filter=pending',
            ],
            [
            'title' => 'Revenue '. $selectedFilter,
            'number' => 'Rp ' . number_format($revenueToday, 0, ',', '.'),
            'class' => 'abu',
            'icon' => 'graph-up-arrow',
            'url' => '/all-transactions?type=BOOKING',
            ],
        ];

        // Room Availability Calendar (30 hari ke depan) berdasarkan Booking
      // Generate calendar days
        $calendarDays = [];
        $calendarStart = Carbon::today();
        $calendarEnd = Carbon::today()->addDays(30);

        for ($d = $calendarStart->copy(); $d->lte($calendarEnd); $d->addDay()) {
            $calendarDays[] = $d->toDateString();
        }

        // Ambil rooms
        $rooms = Rooms::with('subRooms')->where('property_odata', $userKode)->get();
        $roomIds = $rooms->pluck('odata')->toArray();
        $subRoomMetaByOdata = [];
        $subRoomMetaById = [];

        foreach ($rooms as $room) {
            foreach ($room->subRooms as $subRoom) {
                if (!empty($subRoom->odata)) {
                    $subRoomMetaByOdata[$subRoom->odata] = [
                        'odata' => $subRoom->odata,
                        'code_room' => $subRoom->code_room,
                        'name_room' => $subRoom->name_room,
                    ];
                }
                if (!empty($subRoom->id)) {
                    $subRoomMetaById[$subRoom->id] = [
                        'odata' => $subRoom->odata,
                        'code_room' => $subRoom->code_room,
                        'name_room' => $subRoom->name_room,
                    ];
                }
            }
        }

        // 🔥 FIX OVERLAP QUERY
        $bookings = Booking::whereIn('room_odata', $roomIds)
            ->whereIn('status', ['PENDING', 'PAID', 'CONFIRMED', 'BLOCKED', 'PREPARED'])
            ->where(function ($q) use ($calendarStart, $calendarEnd) {
                $q->where('checkin_date', '<', $calendarEnd->toDateString())
                ->where('checkout_date', '>', $calendarStart->toDateString());
            })
            ->with('user')
            ->get();

        // Index booking
        $bookingMap = [];

        foreach ($bookings as $booking) {
            $period = CarbonPeriod::create(
                $booking->checkin_date,
                Carbon::parse($booking->checkout_date)->subDay()
            );

            foreach ($period as $date) {
                $dateStr = $date->toDateString();
                $bookingMap[$booking->room_odata][$dateStr] = $booking;
            }
        }

        // Build calendar
        $roomCalendar = [];

        foreach ($rooms as $room) {

            $row = [
                'room_id' => $room->odata,
                'room_name' => $room->room_name,
                'sub_rooms' => $room->subRooms->map(function ($subRoom) {
                    return [
                        'odata' => $subRoom->odata,
                        'code_room' => $subRoom->code_room,
                        'name_room' => $subRoom->name_room,
                    ];
                })->values()->toArray(),
                'calendar' => []
            ];

            foreach ($calendarDays as $date) {

                $cell = [
                    'date' => $date,
                    'status' => 'available',
                    'booking_user' => null,
                    'booking_odata' => null,
                    'sub_room_odata' => null,
                    'sub_room_code' => null,
                    'sub_room_name' => null,
                    'can_block' => true,
                    'can_open' => false,
                    'type' => null,
                ];

                if (isset($bookingMap[$room->odata][$date])) {
                    $booking = $bookingMap[$room->odata][$date];

                    if ($booking->status === 'BLOCKED') {
                        $cell['status'] = 'blocked';
                    } elseif ($booking->status === 'PREPARED') {
                        $cell['status'] = 'prepared';
                    } else {
                        $cell['status'] = 'booked';
                    }
                    $cell['booking_odata'] = $booking->odata;
                    $cell['booking_user'] = optional($booking->user)->name;

                    $subRoomMeta = null;
                    if (!empty($booking->room_sub_odata) && isset($subRoomMetaByOdata[$booking->room_sub_odata])) {
                        $subRoomMeta = $subRoomMetaByOdata[$booking->room_sub_odata];
                    } elseif (!empty($booking->room_sub_id) && isset($subRoomMetaById[$booking->room_sub_id])) {
                        $subRoomMeta = $subRoomMetaById[$booking->room_sub_id];
                    }

                    if ($subRoomMeta) {
                        $cell['sub_room_odata'] = $subRoomMeta['odata'];
                        $cell['sub_room_code'] = $subRoomMeta['code_room'];
                        $cell['sub_room_name'] = $subRoomMeta['name_room'];
                    }

                    $cell['can_block'] = false;
                    $cell['can_open'] = true;
                    $cell['type'] = $booking->type_booking ?? null;
                }

                $row['calendar'][] = $cell;
            }

            $roomCalendar[] = $row;
        }

        $response['room_availability_calendar'] = [
            'days' => $calendarDays,
            'rooms' => $roomCalendar,
        ];

        // Booking Management: List Booking
        $bookingManagement = Booking::with(['user', 'room', 'property', 'payments'])
            ->where('property_odata', $userKode)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->paginate(20)
            ->through(function($booking) {
            return [
                'odata' => $booking->odata ?? '-',
                'invoice' => $booking->payments[0]->invoice_code ?? '-',
                'guest_name' => $booking->user->name ?? '-',
                'room' => ($booking->property->properties ?? '-') . ' - ' . ($booking->room->room_name ?? '-'),
                'checkin_checkout' => ($booking->checkin_date ?? '-') . ' - ' . ($booking->checkout_date ?? '-'),
                'status' => $booking->status,
                'checkin' => $booking->checkin,
                'check_in' => $booking->check_in ? \Carbon\Carbon::parse($booking->check_in)->format('d M Y H:i') : '-',
                'check_out' => $booking->check_out ? \Carbon\Carbon::parse($booking->check_out)->format('d M Y H:i') : '-',
                'payment_status' => $booking->payments[0]->status ?? '-',
                'actions' => [
                'can_confirm' => $booking->status === 'PENDING',
                'can_cancel' => in_array($booking->status, ['PENDING', 'PAID']),
                'can_checkin' => $booking->status === 'PAID' && $booking->checkin !== 'Y',
                'can_checkout' => $booking->status === 'PAID' && $booking->checkin === 'Y',
                ],
            ];
            });

        $response = [
            'data' => $data,
            'room_availability_calendar' => $response['room_availability_calendar'],
            'booking_management' => $bookingManagement,
        ];
        return $response;
    }

    public function checkinBooking($bookingId)
    {
        $booking = Booking::find($bookingId);
        if (!$booking || $booking->status !== 'PAID' || $booking->checkin === 'Y') {
            throw new HttpResponseException(response()->json(['error' => 'Booking not found or cannot be checked in'], 404));
        }
        $booking->checkin = 'Y';
        $booking->check_in = date('Y-m-d H:i:s');
        $booking->save();
        return true;
    }

    public function checkoutBooking($bookingId)
    {
        $booking = Booking::find($bookingId);
        if (!$booking || $booking->status !== 'PAID' || $booking->checkin !== 'Y') {
            throw new HttpResponseException(response()->json(['error' => 'Booking not found or cannot be checked out'], 404));
        }
        $booking->check_out = date('Y-m-d H:i:s');
        $booking->status = 'COMPLETED'; // Pastikan tetap Y karena sudah check-in
        $booking->save();
        return true;
    }

    public function blockRoom($roomId, $subRoomOdata, $checkinDate, $checkoutDate, $status = 'BLOCKED')
    {
        $status = strtoupper((string) $status);
        if (!in_array($status, ['BLOCKED', 'PREPARED'])) {
            throw new HttpResponseException(response()->json(['error' => 'Invalid room status'], 422));
        }

        $room = Rooms::with('subRooms')->where('odata', $roomId)->first();
        if (!$room) {
            throw new HttpResponseException(response()->json(['error' => 'Room not found'], 404));
        }

        $subRoom = null;
        if (!empty($subRoomOdata)) {
            $subRoom = $room->subRooms->firstWhere('odata', $subRoomOdata);
            if (!$subRoom) {
                throw new HttpResponseException(response()->json(['error' => 'Sub room not found'], 404));
            }
        }

        // Cek apakah ada booking yang overlap dengan tanggal yang ingin diblokir
        $overlap = Booking::where('room_odata', $room->odata)
            ->whereIn('status', ['PAID', 'PENDING', 'CONFIRMED', 'BLOCKED', 'PREPARED'])
            ->where(function ($q) use ($checkinDate, $checkoutDate) {
                $q->where('checkin_date', '<', $checkoutDate)
                ->where('checkout_date', '>', $checkinDate);
            });

        if ($subRoom) {
            $overlap->where('room_sub_odata', $subRoom->odata);
        }

        $overlap = $overlap->exists();

        if (!$subRoom) {
            $roomLevelOverlap = Booking::where('room_odata', $room->odata)
                ->whereIn('status', ['PAID', 'PENDING', 'CONFIRMED', 'BLOCKED', 'PREPARED'])
                ->whereNull('room_sub_odata')
                ->where(function ($q) use ($checkinDate, $checkoutDate) {
                    $q->where('checkin_date', '<', $checkoutDate)
                    ->where('checkout_date', '>', $checkinDate);
                })
                ->exists();

            $overlap = $overlap || $roomLevelOverlap;
        }

        if (!empty($subRoomOdata)) {
            $subRoomOverlapWithoutOdata = Booking::where('room_odata', $room->odata)
                ->whereIn('status', ['PAID', 'PENDING', 'CONFIRMED', 'BLOCKED', 'PREPARED'])
                ->where('room_sub_id', optional($subRoom)->id)
                ->where(function ($q) use ($checkinDate, $checkoutDate) {
                    $q->where('checkin_date', '<', $checkoutDate)
                    ->where('checkout_date', '>', $checkinDate);
                })
                ->exists();

            $overlap = $overlap || $subRoomOverlapWithoutOdata;
        }

        if ($checkinDate >= $checkoutDate) {
            throw new HttpResponseException(response()->json(['error' => 'Check-out date must be greater than check-in date'], 400));
        }

        if ($overlap) {
            throw new HttpResponseException(response()->json(['error' => 'Cannot set room status, it already has a booking in the selected date range'], 400));
        }

        // Buat booking dengan status BLOCKED / PREPARED
        $booking = new Booking();
        $booking->odata = (string) Str::uuid();
        $booking->room_id = $room->id;
        $booking->room_odata = $room->odata;
        $booking->room_sub_id = $subRoom ? $subRoom->id : null;
        $booking->room_sub_odata = $subRoom ? $subRoom->odata : null;
        $booking->user_id = auth()->id();
        $booking->user_odata = auth()->user()->odata;
        $booking->property_odata = $room->property_odata;
        $booking->property_id = $room->property_id;
        $booking->type_booking = 'OFFLINE';
        $booking->checkin_date = $checkinDate;
        $booking->checkout_date = $checkoutDate;
        $booking->total_nights = Carbon::parse($checkinDate)->diffInDays(Carbon::parse($checkoutDate));
        $booking->checkin = 'Y';
        $booking->status = $status;
        $booking->save();

        return true;
    }

    public function prepareRoom($roomId, $subRoomOdata, $checkinDate, $checkoutDate)
    {
        return $this->blockRoom($roomId, $subRoomOdata, $checkinDate, $checkoutDate, 'PREPARED');
    }

    public function openRoom($bookingOdata)
    {
        $booking = Booking::where('odata', $bookingOdata)->first();
        if (!$booking || !in_array($booking->status, ['BLOCKED', 'PREPARED'])) {
            throw new HttpResponseException(response()->json(['error' => 'Booking not found or cannot be opened'], 404));
        }
        $booking->delete();
        return true;
    }

    public function getCRMData($month)
    {
        $property = Properties::where('isActive', 0)->count();
        $room = RoomSub::where('status', 0)->count();

        // Filter by month
        $startOfMonth = $month . '-01';
        $endOfMonth = date('Y-m-t', strtotime($startOfMonth));

        // Total Booking (count bookings in month)
        $totalBooking = Booking::where('status','COMPLETED')
            ->whereBetween('checkin_date', [$startOfMonth, $endOfMonth])
            ->count();

        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth   = Carbon::parse($month)->endOfMonth();

        $validStatus = ['PAID', 'PREPARED', 'COMPLETED'];

        $roomNightsSold = Booking::whereIn('status', $validStatus)
            ->where('checkin_date', '<=', $endOfMonth)
            ->where('checkout_date', '>=', $startOfMonth)
            ->get()
            ->sum(function ($booking) use ($startOfMonth, $endOfMonth) {

                $checkin  = Carbon::parse($booking->checkin_date);
                $checkout = Carbon::parse($booking->checkout_date);

                $start = $checkin->greaterThan($startOfMonth) ? $checkin : $startOfMonth;
                $end   = $checkout->lessThan($endOfMonth) ? $checkout : $endOfMonth;

                return $start->diffInDays($end);
            });

        // Total hari dalam bulan
        $daysInMonth = $startOfMonth->daysInMonth;

        // Available Room Nights
        $availableRoomNights = $room * $daysInMonth;

        // Occupancy Rate
        $occupancyRate = $availableRoomNights > 0
            ? round(($roomNightsSold / $availableRoomNights) * 100, 2)
            : 0;

        $parameter = Parameter::first();

        // Total Revenue (sum of PAID booking transactions in month)
        $totalRevenue = Transactions::where('status', 'PAID')
            ->where('type', 'BOOKING')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total_amount');

        // Add-On Revenue (sum of PAID add-on transactions in month)
        $addOnRevenue = Transactions::where('status', 'PAID')
            ->where('type', 'ADDON')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total_amount');

        // Repeat Booking (count users with more than 1 booking in month)
        $repeatBooking = Booking::where('status', 'COMPLETED')
            ->whereBetween('checkin_date', [$startOfMonth, $endOfMonth])
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->count();

        // Repeat Rate (repeatBooking / totalBooking * 100)
        $repeatRate = $totalBooking > 0 ? round(($repeatBooking / $totalBooking) * 100, 2) : 0;

        $response = [
            'total_properties' => $property,
            'total_booking' => $totalBooking,
            'room_nights_sold' => $roomNightsSold,
            'occupancy_rate' => $occupancyRate,
            'target_occupancy' => $parameter->target_occupancy ?? 0,
            'total_revenue' => $totalRevenue,
            'add_on_revenue' => $addOnRevenue,
            'repeat_booking' => $repeatBooking,
            'repeat_rate' => $repeatRate,
            'total_komisi_cro' => $parameter && $parameter->rate_komisi ? round(($parameter->rate_komisi / 100) * $totalRevenue, 0) : 0,
        ];

        return $response;
    }
}
