<?php

namespace App\Http\Controllers\Api\TimeLogs;

use App\Http\Controllers\Controller;
use App\Models\TimeLogs;
use Illuminate\Http\Request;

class TimeLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'description' => 'nullable|string',
        ]);

        $log = TimeLogs::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Time log created',
            'data' => $log->load('project'),
            'hours' => $log->hours,
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(TimeLogs $timeLogs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TimeLogs $timeLogs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TimeLogs $timeLogs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TimeLogs $timeLogs)
    {
        //
    }
}
