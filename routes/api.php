<?php

namespace App\Http\Controllers\Api;

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Web\DnTourTravelController;
use App\Http\Controllers\Api\Web\PushNotificationController;
use App\Http\Controllers\ForgotPassword;
use App\Mail\ForgotPasswordEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // User Geo Ip Detection
    Route::get('/detect-ip', [DnTourTravelController::class, 'get_your_ip']);
    Route::get('/geo-location/{apiToken}/{ip}', [DnTourTravelController::class, 'geo_location']);

    // Web fiture handler
    Route::post('/sending-message/{apiToken}', [DnTourTravelController::class, 'sending_message']);
    Route::post('/push-notif', [PushNotificationController::class, 'push_notification']);

    // Forgot password route api
    Route::post('/forgot/{apiToken}', [ForgotPasswordController::class, 'forgot']);
    Route::get('/password-reset-data/{email}', [ForgotPasswordController::class, 'password_reset']);
    Route::get('/check-reset-token/{token}', [ForgotPasswordController::class, 'checkTokenReset']);
    Route::post('/reset', [ForgotPasswordController::class, 'reset_password']);
});

Route::prefix('v1/indonesia-area')->group(function () {
    // Route province
    Route::get('/province', [DnTourTravelController::class, 'province']);
    Route::get('/regencies/{province_id}', [DnTourTravelController::class, 'regencies']);
    Route::get('/district/{regency_id}', [DnTourTravelController::class, 'district']);
    Route::get('/villages/{district_id}', [DnTourTravelController::class, 'villages']);
});

// middleware guard auth
Route::middleware('auth:api')->get('/user', [LoginController::class, 'userIsLogin']);

// Authentication route
Route::prefix('v1/auth')->group(function () {
    // authentication
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/user-inactive/{token}', [UserActivationTokenController::class, 'activation_data']);
    Route::put('/activation/{user_id}', [RegisterController::class, 'activation']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:api');

    // provider oauth
    Route::get('/redirect/{provider}', [RedirectProviderController::class, 'redirectToProvider']);

    Route::get('/{provider}/callback', [RedirectProviderController::class, 'handleProviderCallback']);
});
