<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('api')->group(function () {
    // Маршруты для подписчиков
    Route::apiResource('subscribers', SubscriberController::class);
    
    // Маршруты для подписок
    Route::apiResource('subscriptions', SubscriptionController::class);
});

Route::group(['middleware' => 'auth.keycloak'], function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::get('test', [AuthController::class, 'test']);
    Route::get('roles', [AuthController::class, 'roles']);
    Route::get('has-role/{role}', [AuthController::class, 'hasRole']);
    Route::get('validate-token', [AuthController::class, 'validateToken']);
    Route::get('permissions', [AuthController::class, 'permissions']);
    Route::get('token-info', [AuthController::class, 'tokenInfo']);
});