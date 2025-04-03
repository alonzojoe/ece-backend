<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PositionController;
use App\Http\Controllers\API\SensorController;
use App\Http\Controllers\API\NotificationController;


Route::get('/test', function () {
    return response()->json(['status' => 'success', 'message' => 'API Endpoint Works!'], 200);
});



Route::group(['prefix' => '/auth'], function () {
    Route::get('/', [AuthController::class, 'index']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/me', [AuthController::class, 'me']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::patch('/update/{id}', [AuthController::class, 'update']);
    Route::patch('/deactivate/{id}', [AuthController::class, 'deactivate']);
});

Route::group(['prefix' => '/sensor'], function () {
    Route::get('/', [SensorController::class, 'index']);
    Route::post('/store', [SensorController::class, 'store']);
    Route::put('/update/{id}', [SensorController::class, 'update']);
    Route::patch('/inactive/{id}', [SensorController::class, 'inactive']);
});

Route::group(['prefix' => '/position'], function () {
    Route::get('/', [PositionController::class, 'index']);
    Route::get('/all', [PositionController::class, 'all']);
    Route::post('/store', [PositionController::class, 'store']);
    Route::put('/update/{id}', [PositionController::class, 'update']);
    Route::patch('/delete/{id}', [PositionController::class, 'destroy']);
});

Route::group(['prefix' => '/notif'], function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::get('/all', [NotificationController::class, 'all']);
    Route::post('/store', [NotificationController::class, 'store']);
    Route::patch('/update/{id}', [NotificationController::class, 'update']);
    Route::patch('/seen', [NotificationController::class, 'seen']);
});
