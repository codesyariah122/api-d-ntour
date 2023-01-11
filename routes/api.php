<?php

namespace App\Http\Controllers\Api;

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Web\DnTourTravelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // User Geo Ip Detection
    Route::get('/detect-ip', [DnTourTravelController::class, 'get_your_ip']);
    Route::get('/geo-location/{apiToken}/{ip}', [DnTourTravelController::class, 'geo_location']);

    // Web fiture handler
    Route::post('/sending-message/{apiToken}', [DnTourTravelController::class, 'sending_message']);
});

// middleware guard auth
Route::middleware('auth:api')->get('/user', [LoginController::class, 'userIsLogin']);

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
