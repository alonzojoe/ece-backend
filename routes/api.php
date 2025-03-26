<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SensorController;
use App\Models\SensorData;

Route::get('/test', function () {
    return response()->json(['status' => 'success', 'message' => 'API Endpoint Works!'], 200);
});



Route::group(['prefix' => '/auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/me', [AuthController::class, 'me']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
});

Route::group(['prefix' => '/sensor'], function () {
    Route::get('/', [SensorController::class, 'index']);
    Route::post('/store', [SensorController::class, 'store']);
    Route::put('/update/{id}', [SensorController::class, 'update']);
    Route::patch('/inactive/{id}', [SensorController::class, 'inactive']);
});
