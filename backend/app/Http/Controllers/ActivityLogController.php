<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;

class ActivityLogController extends Controller
{
    /**
     * Get all activity logs
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 15);
            $subject = $request->input('subject_type');
            $causer = $request->input('causer_id');
            $event = $request->input('event');

            $query = Activity::with(['causer', 'subject'])
                ->latest();

            // Filter by subject type (e.g., App\Models\User)
            if ($subject) {
                $query->where('subject_type', $subject);
            }

            // Filter by causer (user who performed the action)
            if ($causer) {
                $query->where('causer_id', $causer);
            }

            // Filter by event type (created, updated, deleted)
            if ($event) {
                $query->where('event', $event);
            }

            $logs = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $logs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch activity logs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get activity logs for specific user
     */
    public function userLogs($userId, Request $request)
    {
        try {
            $perPage = $request->input('per_page', 15);
            
            $user = User::findOrFail($userId);
            
            // Get logs where user is the subject (actions performed on this user)
            $subjectLogs = Activity::where('subject_type', User::class)
                ->where('subject_id', $userId)
                ->with('causer')
                ->latest()
                ->paginate($perPage);

            // Get logs where user is the causer (actions performed by this user)
            $causerLogs = Activity::where('causer_type', User::class)
                ->where('causer_id', $userId)
                ->with('subject')
                ->latest()
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user,
                    'actions_on_user' => $subjectLogs,
                    'actions_by_user' => $causerLogs
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user activity logs',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get activity log detail
     */
    public function show($id)
    {
        try {
            $log = Activity::with(['causer', 'subject'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $log
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Activity log not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Delete activity log
     */
    public function destroy($id)
    {
        try {
            $log = Activity::findOrFail($id);
            $log->delete();

            return response()->json([
                'success' => true,
                'message' => 'Activity log deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete activity log',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear all activity logs
     */
    public function clearAll()
    {
        try {
            Activity::truncate();

            return response()->json([
                'success' => true,
                'message' => 'All activity logs cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear activity logs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get activity statistics
     */
    public function statistics()
    {
        try {
            $stats = [
                'total_activities' => Activity::count(),
                'today_activities' => Activity::whereDate('created_at', today())->count(),
                'this_week_activities' => Activity::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'this_month_activities' => Activity::whereMonth('created_at', now()->month)->count(),
                'by_event' => Activity::selectRaw('event, COUNT(*) as count')
                    ->groupBy('event')
                    ->get(),
                'top_users' => Activity::selectRaw('causer_id, causer_type, COUNT(*) as count')
                    ->whereNotNull('causer_id')
                    ->groupBy('causer_id', 'causer_type')
                    ->orderByDesc('count')
                    ->limit(10)
                    ->with('causer')
                    ->get()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch activity statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
