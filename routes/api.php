<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Client\ClientController;
use App\Http\Controllers\Api\Projects\ProjectController;
use App\Http\Controllers\Api\TimeLogs\TimeLogsController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

// After Login
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('/clients', ClientController::class);
    Route::apiResource('/project', ProjectController::class);
    Route::apiResource('/timelog', TimeLogsController::class);
   
});
//  Route::put('clients/update/{id}', [ClientController::class,'update']);
//  Route::post('clients/update/{id}', ClientController::class,'store');