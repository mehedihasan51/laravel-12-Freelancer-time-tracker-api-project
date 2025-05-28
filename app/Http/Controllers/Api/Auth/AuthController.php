<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Api\Auth\LoginResource;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'freelancer',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'token' => $token,
        ];

        return response()->json([
            'status'     => true,
            'message'    => 'User Register in successfully.',
            'code'       => 200,
            'data' => $data,
        ]);
    }

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
                'code' => 401
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login Successfully',
            'code'    => 201,
            'token_type' => 'bearer',
            'token' => $token,
            'data' => new LoginResource($user),
        ],201);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => true,
            'message' => 'User logged out Successfully'
        ]);
    }
}
