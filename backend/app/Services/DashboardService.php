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


        // Booking Status Trend breakdown (daily, weekly, monthly, yearly)
        $statusList = ['PENDING', 'PAID', 'CANCELLED', 'EXPIRED', 'COMPLETED'];
        $bookingStatusTrendFormat = function($arr) use ($statusList) {
            $dates = array_map(function($item) { return $item['date']; }, $arr);
            $statusCounts = [];
            foreach ($statusList as $status) {
                $statusCounts[$status] = array_map(function($item) use ($status) {
                    return $item[$status] ?? 0;
                }, $arr);
            }
            if (empty($dates)) {
                $result = ['dates' => []];
                foreach ($statusList as $status) {
                    $result[$status] = [0];
                }
                return $result;
            }
            $result = ['dates' => $dates];
            foreach ($statusList as $status) {
                $result[$status] = $statusCounts[$status];
            }
            return $result;
        };

        // Format daily, weekly, monthly, yearly booking status trend
        $bookingDailyStatus = [];
        foreach ($daily as $item) {
            $row = ['date' => $item['date']];
            foreach ($statusList as $status) {
                $row[$status] = Booking::whereDate('created_at', $item['date'])->where('type_booking', 'ONLINE')->where('status', $status)->count();
            }
            $bookingDailyStatus[] = $row;
        }

        $bookingWeeklyStatus = [];
        foreach ($weekly as $item) {
            $row = ['date' => $item['date']];
            foreach ($statusList as $status) {
                $row[$status] = Booking::whereDate('created_at', $item['date'])->where('type_booking', 'ONLINE')->where('status', $status)->count();
            }
            $bookingWeeklyStatus[] = $row;
        }

        $bookingMonthlyStatus = [];
        foreach ($monthly as $item) {
            $row = ['date' => $item['date']];
            foreach ($statusList as $status) {
                $row[$status] = Booking::whereDate('created_at', $item['date'])->where('type_booking', 'ONLINE')->where('status', $status)->count();
            }
            $bookingMonthlyStatus[] = $row;
        }

        $bookingYearlyStatus = [];
        foreach ($yearly as $item) {
            $row = ['date' => $item['date']];
            foreach ($statusList as $status) {
                $row[$status] = Booking::whereYear('created_at', substr($item['date'], 0, 4))
                    ->whereMonth('created_at', substr($item['date'], 5, 2))
                    ->where('type_booking', 'ONLINE')
                    ->where('status', $status)
                    ->count();
            }
            $bookingYearlyStatus[] = $row;
        }

        $response['booking_status_trend'] = [
            'daily' => $bookingStatusTrendFormat($bookingDailyStatus),
            'weekly' => $bookingStatusTrendFormat($bookingWeeklyStatus),
            'monthly' => $bookingStatusTrendFormat($bookingMonthlyStatus),
            'yearly' => $bookingStatusTrendFormat($bookingYearlyStatus),
        ];

        // Membership Status Trend breakdown (daily, weekly, monthly, yearly)

        $membershipStatusList = ['PENDING', 'ACTIVE', 'EXPIRED', 'CANCELLED'];
        $membershipStatusTrendFormat = function($arr) use ($membershipStatusList) {
            $dates = array_map(function($item) { return $item['date']; }, $arr);
            $statusCounts = [];
            foreach ($membershipStatusList as $status) {
                $statusCounts[$status] = array_map(function($item) use ($status) {
                    return $item[$status] ?? 0;
                }, $arr);
            }
            if (empty($dates)) {
                $result = ['dates' => []];
                foreach ($membershipStatusList as $status) {
                    $result[$status] = [0];
                }
                return $result;
            }
            $result = ['dates' => $dates];
            foreach ($membershipStatusList as $status) {
                $result[$status] = $statusCounts[$status];
            }
            return $result;
        };

        // Format daily, weekly, monthly, yearly membership status trend

        $revueMembership = Transactions::whereBetween('created_at', [$startDate, $endDate])
        ->where('type', 'MEMBERSHIP')
        ->where('status', 'PAID')
        ->sum('amount');

        $membershipDailyStatus = [];
        foreach ($daily as $item) {
            $row = ['date' => $item['date']];
            foreach ($membershipStatusList as $status) {
                $row[$status] = UserMembership::whereDate('created_at', $item['date'])->where('status', $status)->count();
            }
            $membershipDailyStatus[] = $row;
        }

        $membershipWeeklyStatus = [];
        foreach ($weekly as $item) {
            $row = ['date' => $item['date']];
            foreach ($membershipStatusList as $status) {
                $row[$status] = UserMembership::whereDate('created_at', $item['date'])->where('status', $status)->count();
            }
            $membershipWeeklyStatus[] = $row;
        }

        $membershipMonthlyStatus = [];
        foreach ($monthly as $item) {
            $row = ['date' => $item['date']];
            foreach ($membershipStatusList as $status) {
                $row[$status] = UserMembership::whereDate('created_at', $item['date'])->where('status', $status)->count();
            }
            $membershipMonthlyStatus[] = $row;
        }

        $membershipYearlyStatus = [];
        foreach ($yearly as $item) {
            $row = ['date' => $item['date']];
            foreach ($membershipStatusList as $status) {
                $row[$status] = UserMembership::whereYear('created_at', substr($item['date'], 0, 4))
                    ->whereMonth('created_at', substr($item['date'], 5, 2))
                    ->where('status', $status)
                    ->count();
            }
            $membershipYearlyStatus[] = $row;
        }

        $response['membership_status_trend'] = [
            'daily' => $membershipStatusTrendFormat($membershipDailyStatus),
            'weekly' => $membershipStatusTrendFormat($membershipWeeklyStatus),
            'monthly' => $membershipStatusTrendFormat($membershipMonthlyStatus),
            'yearly' => $membershipStatusTrendFormat($membershipYearlyStatus),
        ];

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
            'booking_status_trend' => $response['booking_status_trend'],
            'membership_status_trend' => $response['membership_status_trend'],
            'dataMembership' => $dataMembership,
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
        $rooms = Rooms::when($property, function ($query) use ($property) {
            $query->where('property_odata', $property);
        })->get();

        $roomIds = $rooms->pluck('odata')->toArray();

        // 🔥 FIX OVERLAP QUERY
        $bookings = Booking::whereIn('room_odata', $roomIds)
            ->whereIn('status', ['PAID', 'CONFIRMED', 'COMPLETED'])
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
                'calendar' => []
            ];

            foreach ($calendarDays as $date) {

                $cell = [
                    'date' => $date,
                    'status' => 'available',
                    'booking_user' => null,
                    'booking_odata' => null,
                    'can_block' => true,
                    'can_open' => false,
                ];

                if (isset($bookingMap[$room->odata][$date])) {
                    $booking = $bookingMap[$room->odata][$date];

                    $cell['status'] = 'booked';
                    $cell['booking_odata'] = $booking->odata;
                    $cell['booking_user'] = optional($booking->user)->name;
                    $cell['can_block'] = false;
                    $cell['can_open'] = false;
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
        $rooms = Rooms::where('property_odata', $userKode)->get();
        $roomIds = $rooms->pluck('odata')->toArray();

        // 🔥 FIX OVERLAP QUERY
        $bookings = Booking::whereIn('room_odata', $roomIds)
            ->whereIn('status', ['PAID', 'CONFIRMED', 'COMPLETED','BLOCKED'])
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
                'calendar' => []
            ];

            foreach ($calendarDays as $date) {

                $cell = [
                    'date' => $date,
                    'status' => 'available',
                    'booking_user' => null,
                    'booking_odata' => null,
                    'can_block' => true,
                    'can_open' => false,
                    'type' => null,
                ];

                if (isset($bookingMap[$room->odata][$date])) {
                    $booking = $bookingMap[$room->odata][$date];

                    $cell['status'] = $booking->status === 'BLOCKED' ? 'blocked' : 'booked';
                    $cell['booking_odata'] = $booking->odata;
                    $cell['booking_user'] = optional($booking->user)->name;
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

    public function blockRoom($roomId, $checkinDate, $checkoutDate)
    {
        $room = Rooms::where('odata', $roomId)->first();
        if (!$room) {
            throw new HttpResponseException(response()->json(['error' => 'Room not found'], 404));
        }
        // Cek apakah ada booking yang overlap dengan tanggal yang ingin diblokir
        $overlap = Booking::where('room_odata', $room->odata)
            ->whereIn('status', ['PAID', 'PENDING', 'COMPLETED'])
            ->where(function ($q) use ($checkinDate, $checkoutDate) {
                $q->where('checkin_date', '<', $checkoutDate)
                ->where('checkout_date', '>', $checkinDate);
            })
            ->exists();

        if ($overlap) {
            throw new HttpResponseException(response()->json(['error' => 'Cannot block room, it is already booked in the selected date range'], 400));
        }

        // Buat booking dengan status BLOCKED
        $booking = new Booking();
        $booking->odata = (string) Str::uuid();
        $booking->room_id = $room->id;
        $booking->room_odata = $room->odata;
        $booking->user_id = auth()->id();
        $booking->user_odata = auth()->user()->odata;
        $booking->property_odata = $room->property_odata;
        $booking->property_id = $room->property_id;
        $booking->type_booking = 'OFFLINE';
        $booking->checkin_date = $checkinDate;
        $booking->checkout_date = $checkoutDate;
        $booking->total_nights = Carbon::parse($checkinDate)->diffInDays(Carbon::parse($checkoutDate));
        $booking->checkin = 'Y';
        $booking->status = 'BLOCKED';
        $booking->save();

        return true;
    }

    public function openRoom($bookingOdata)
    {
        $booking = Booking::where('odata', $bookingOdata)->first();
        if (!$booking || $booking->status !== 'BLOCKED') {
            throw new HttpResponseException(response()->json(['error' => 'Booking not found or cannot be unblocked'], 404));
        }
        $booking->delete();
        return true;
    }
}
