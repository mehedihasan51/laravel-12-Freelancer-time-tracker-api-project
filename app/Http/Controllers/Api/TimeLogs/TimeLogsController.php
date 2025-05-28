<?php

namespace App\Http\Controllers\Api\TimeLogs;

use App\Models\TimeLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TimeLog\TimeLogEndResource;
use App\Http\Resources\Api\TimeLog\TimeLogResource;
use App\Http\Resources\Api\TimeLog\TimeLogStartResource;
use Illuminate\Support\Facades\Cache;
use App\Notifications\DailyLimitReachedNotification;
use Illuminate\Support\Facades\Notification;

class TimeLogsController extends Controller
{


    /**
     * View logs by day/week
     * 
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'day');

        $query = TimeLogs::with('project');

        if ($period == 'day') {
            $query->whereDate('start_time', today());
        } elseif ($period == 'week') {
            $query->whereBetween('start_time', [now()->startOfWeek(), now()->endOfWeek()]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Logs retrieved successfully',
            'code' => 200,
            'total_hours' => $query->sum('hours'),
            'logs' => TimeLogResource::collection($query->get())

        ]);
    }

    /**
     * Display a listing of the resource.
     * Start a time log
     */


    public function startLog(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required',
            'client_id' => 'required',
        ]);

        $log = [
            'project_id' => $validated['project_id'],
            'client_id' => $validated['client_id'],
            'start_time' => now(),
            'description' => 'Started log',
        ];
        $total = 8; 
        $userId = auth()->id();
        $dateKey = now()->toDateString();
        $cacheKey = "notified_{$userId}_{$dateKey}";

        if ($total >= 8 && !Cache::has($cacheKey)) {
            Notification::route('mail', auth()->user()->email)
                ->notify(new DailyLimitReachedNotification($total));
            Cache::put($cacheKey, true, now()->endOfDay());

            return response()->json([
                'status' => true,
                'message' => 'Daily limit reached, notification sent',
                'code' => 200,
                'data' => $log
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Log started successfully',
            'code' => 200,
            'data' => $log
        ]);
    }


    /**
     * End a time log
     * 
     */
    public function endLog($id)
    {
        $log = TimeLogs::findOrFail($id);
        if ($log->end_time) return response()->json([
            'status' => false,
            'code' => 400,
            'message' => 'Log already ended'
        ], 400);

        $log->end_time = now();
        $log->hours = round(Carbon::parse($log->start_time)->diffInMinutes(now()) / 60, 2);
        $log->save();

        return response()->json([
            'status' => true,
            'message' => 'Log ended successfully',
            'code' => 200,
            'data' => new TimeLogEndResource($log)
        ]);
    }

    /**
     * Add Manual TimeLog
     * 
     */

    public function manualLog(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'client_id' => 'required|exists:clients,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'description' => 'nullable|string',
        ]);

        $log = TimeLogs::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Log created successfully',
            'code' => 200,
            'data' => new TimeLogResource($log),
            'hours' => $log->hours,
        ]);
    }

    /**
     * Report filtering by client/project/day
     * 
     */

    public function getReport(Request $request)
    {
        $request->validate([
            'from'      => 'required|date',
            'to'        => 'required|date',
            'client_id' => 'nullable|integer',
            'project_id' => 'nullable|integer',
        ]);

        $query = TimeLogs::with('project.client')
            ->whereBetween('start_time', [
                $request->from,
                $request->to,
            ]);

        if ($request->client_id) {
            $query->where('client_id', $request->client_id);
        }
        if ($request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        $logs = $query->get();

        if ($request->client_id && $logs->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => "No logs found for client_id={$request->client_id}",
                'code'    => 404,
            ], 404);
        }
        if (! $request->client_id && $logs->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'No logs found for the given date range',
                'code'    => 404,
            ], 404);
        }
        $perDay = $logs
            ->groupBy(fn($log) => Carbon::parse($log->start_time)->toDateString())
            ->count();

        $perProjects = $logs
            ->pluck('project_id')
            ->unique()
            ->count();

        $projectsPerClient = $logs
            ->groupBy(fn($log) => $log->client_id)
            ->map(
                fn($logsForClient) => $logsForClient
                    ->pluck('project_id')
                    ->unique()
                    ->count()
            );

        return response()->json([
            'status'  => true,
            'message' => 'Report retrieved successfully',
            'code'    => 200,
            'data'    => [
                'per_day'             => $perDay,
                'per_projects'        => $perProjects,
                'projects_per_client' => $projectsPerClient,
            ],
        ]);
    }
}
