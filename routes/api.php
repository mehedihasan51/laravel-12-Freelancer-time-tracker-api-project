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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// After Login
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('/clients', ClientController::class);
    Route::apiResource('/project', ProjectController::class);
    Route::apiResource('/timelog', TimeLogsController::class);

});
Route::prefix('time-logs')->group(function () {
    Route::post('/start', [TimeLogsController::class, 'startLog']);
    // Route::post('/timelog', [TimeLogsController::class, 'store']);
    Route::post('/end/{id}', [TimeLogsController::class, 'endLog']);
    Route::post('/manual', [TimeLogsController::class, 'manualLog']);
    Route::get('/', [TimeLogsController::class, 'index']);
});

Route::get('/report', [TimeLogsController::class, 'getReport']);
