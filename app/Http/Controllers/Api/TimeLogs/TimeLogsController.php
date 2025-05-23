<?php

namespace App\Http\Controllers\Api\TimeLogs;

use App\Models\TimeLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TimeLog\TimeLogEndResource;
use App\Http\Resources\Api\TimeLog\TimeLogResource;
use App\Http\Resources\Api\TimeLog\TimeLogStartResource;

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
            'project_id' => 'required|exists:projects,id',
            'client_id' => 'required|exists:clients,id',
        ]);

        $log = TimeLogs::create([
            'project_id' => $validated['project_id'],
            'client_id' => $validated['client_id'],
            'start_time' => now(),
            'description' => 'Started log',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Log started successfully',
            'code' => 200,
            'data' => new TimeLogStartResource($log)
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
    // public function report(Request $request)
    // {
    //     $request->validate([
    //         'from' => 'required|date',
    //         'to' => 'required|date|after_or_equal:from',
    //     ]);


    //     $query = TimeLogs::query()
    //         ->whereBetween('start_time', [$request->from, $request->to]);

    //     if ($request->has('client_id')) {
    //         $query->whereHas('project.client', function ($q) use ($request) {
    //             $q->where('id', $request->client_id);
    //         });
    //     }

    //     if ($request->has('project_id')) {
    //         $query->where('project_id', $request->project_id);
    //     }

    //     $logs = $query->with(['project.client'])->get();

    //     $totalHours = $logs->sum('hours');

    //     return response()->json([
    //         'status' => true,
    //         'total_hours' => $totalHours,
    //         'logs' => TimeLogResource::collection($logs)
    //     ]);
    // }


    // public function getReport(Request $request)
    // {
    //     $request->validate([
    //         'from' => 'required|date',
    //         'to' => 'required|date',
    //         'clint_id' => 'nullable|integer',
    //         'project_id' => 'nullable|integer',
    //     ]);

    //     $query = TimeLogs::with(['project.client']) // eager load
    //         ->whereBetween('start_time', [$request->from, $request->to]);

    //     if ($request->clint_id) {
    //         $query->whereHas('project', function ($q) use ($request) {
    //             $q->where('client_id', $request->clint_id);
    //         });
    //     }

    //     // per day
    //     if ($request->has('day')) {
    //         $query->whereDate('start_time', 'end_date', $request->day);
    //     }

    //     if ($request->project_id) {
    //         $query->where('project_id', $request->project_id);
    //     }

    //     $logs = $query->get();

    //     if ($logs->isEmpty()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'No logs found for the given criteria',
    //             'code' => 404,
    //         ], 404);
    //     }

    //     $data = [

    //         'per_projects' => $logs->unique('project_id')->count(),
    //         'per_day' => $logs->unique('day')->count(),
    //         'per_clients' => $logs->unique('client_id')->count(),
    //         // 'total_hours' => $logs->sum('hours'),
    //     ];

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Report retrieved successfully',
    //         'code' => 200,
    //         'data' => $data,

    //     ]);
    // }



 public function getReport(Request $request)
{
    $request->validate([
        'from' => 'required|date',
        'to' => 'required|date',
        'clint_id' => 'nullable|integer',
        'project_id' => 'nullable|integer',
    ]);

    $query = TimeLogs::with(['project.client'])
        ->whereBetween('start_time', [$request->from, $request->to]);

    if ($request->clint_id) {
        $query->whereHas('project', function ($q) use ($request) {
            $q->where('client_id', $request->clint_id);
        });
    }

    if ($request->project_id) {
        $query->where('project_id', $request->project_id);
    }

    $logs = $query->get();

    if ($logs->isEmpty()) {
        return response()->json([
            'status' => false,
            'message' => 'No logs found for the given criteria',
            'code' => 404,
        ], 404);
    }

    // Group by day
    $perDay = $logs->groupBy(function ($log) {
        return Carbon::parse($log->start_time)->format('Y-m-d');
    })->map(function ($group) {
        return $group->sum('hours');
    });

    // Unique clients from related projects
    $uniqueClients = $logs->pluck('project.client.id')->unique()->filter();

    // Unique projects
    $uniqueProjects = $logs->pluck('project_id')->unique();

    // Count how many projects per client
    $projectsPerClient = $logs->groupBy('project.client.id')->map(function ($logs) {
        return $logs->pluck('project_id')->unique()->count();
    });

    $data = [
        'per_day' => $perDay,
        'total_clients' => $uniqueClients->count(),
        'total_projects' => $uniqueProjects->count(),
        'total_hours' => $logs->sum('hours'),
        'projects_per_client' => $projectsPerClient,
    ];

    return response()->json([
        'status' => true,
        'message' => 'Report retrieved successfully',
        'code' => 200,
        'data' => $data,
    ]);
}
}
