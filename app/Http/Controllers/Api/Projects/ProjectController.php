<?php

namespace App\Http\Controllers\Api\Projects;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Project\ProjectResource;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $projects = Project::whereHas('client', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->with('client')->latest()->get();

        return response()->json([
            'status' => true,
            'data' => $projects
        ]);
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
            'client_id'   => 'required|exists:clients,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'in:active,completed',
            'deadline'    => 'nullable|date',
        ]);

        // Ensure the client belongs to the authenticated user
        $client = $request->user()->clients()->findOrFail($data['client_id']);

        $project = Project::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Project created successfully',
            'code' => 201,
            'data' => new ProjectResource($project)
        ],201);
    }

    /**
     * Display the specified resource.
     */
    // Show a single project
    public function show($id)
    {
        $project = Project::with('client')->findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => $project
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        // Ensure the project belongs to the user's client
        if ($request->user()->id !== $project->client->user_id) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'title'       => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'in:active,completed',
            'deadline'    => 'nullable|date',
        ]);

        $project->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Project updated successfully',
            'data' => $project
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */

    // Delete a project
    public function destroy(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        if ($request->user()->id !== $project->client->user_id) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $project->delete();

        return response()->json([
            'status' => true,
            'message' => 'Project deleted successfully'
        ]);
    }
}
