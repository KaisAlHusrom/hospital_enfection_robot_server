<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

Route::group(['prefix' => 'v1'], function () {
    Route::get("test", function () {

        return response()->json(['message' => 'API is working fine.']);
    });

    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        // Route::post('/login', [AuthController::class, 'login']);
        // Route::middleware(['auth:sanctum'])->prefix('user')->group(function () {
        //     Route::get("/", [AuthController::class, 'getUser']);
        //     Route::get("/is_admin", [AuthController::class, 'isAdmin']);
        // });

        // Route::post('/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum', 'throttle:60,1']);
        // Route::get('/user', [AuthController::class, 'getUser'])->middleware(['auth:sanctum', 'throttle:60,1']);

        // Route::post('/refresh', [AuthController::class,'refresh']);
    });

    Route::middleware('auth:sanctum')->group(function () {});
});
