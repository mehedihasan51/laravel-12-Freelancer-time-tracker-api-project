<?php

namespace App\Http\Controllers\Api\Client;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Client\ClientResource;

class ClientController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email',
            'contact_person' => 'nullable|string|max:255',
        ]);

        $client = $request->user()->clients()->create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Client Info Create Successfully',
            'code'    => 201,
            'data' => new ClientResource($client),
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'contact_person' => 'nullable|string|max:255',
        ]);


        $client = Client::findOrFail($id);

        if ($request->user()->id !== $client->user_id) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized to update this client.'
            ], 403);
        }

        $updated = $client->update($request->only(['name', 'email', 'contact_person']));

        if (!$updated) {
            return response()->json([
                'status' => false,
                 'message' => 'Client not updated.',
                 'code' => 500,
                 'data' => null,
                ], 500);
        }

        return response()->json([
            'status' => true,
            'message' => 'Client Info Updated Successfully',
            'code' => 200,
            'data' => new ClientResource($client),
        ]);
    }

}
