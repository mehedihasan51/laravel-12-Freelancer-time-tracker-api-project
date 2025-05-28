<?php

namespace App\Http\Controllers\Api\Projects;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Client\ClientResource;
use App\Http\Resources\Api\Project\ProjectResource;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     * List all projects for a specific freelancer (based on authenticated user's clients)
     */
    public function index(Request $request)
    {
        $projects = Project::whereHas('client', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->with('client')->latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'Projects retrieved successfully',
            'code' => 200,
            'data' => ProjectResource::collection($projects),
        ]);
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

       
        $client = $request->user()->clients()->findOrFail($data['client_id']);

        $project = Project::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Project created successfully',
            'code' => 201,
            'data' => new ProjectResource($project)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $project = Project::with('client')->findOrFail($id);

        return response()->json([
            'status' => true,
            'message' => 'Project retrieved successfully',
            'code' => 200,
            'data' => new ProjectResource($project),
            
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

    
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
            'code' => 200,
            'data' => new ProjectResource($project)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */

   
    public function destroy(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        if ($request->user()->id !== $project->client->user_id) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $project->delete();

        return response()->json([
            'status' => true,
            'message' => 'Project deleted successfully',
            'code' => 200,
            'data' => null
        ]);
    }
}
