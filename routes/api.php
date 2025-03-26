<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PositionController;
use App\Http\Controllers\API\SensorController;


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

Route::group(['prefix' => '/position'], function () {
    Route::get('/', [PositionController::class, 'index']);
    Route::post('/store', [PositionController::class, 'store']);
    Route::put('/update/{id}', [PositionController::class, 'update']);
    Route::delete('/delete/{id}', [PositionController::class, 'destroy']);
});
