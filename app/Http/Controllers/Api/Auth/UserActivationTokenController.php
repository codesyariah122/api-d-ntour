<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserActivation;
use App\Models\Profile;

class UserActivationTokenController extends Controller
{
    public function activation_data($token)
    {
        try {
            $activationData = UserActivation::whereToken($token)
                ->with(['users'])
                ->first();
            return response()->json([
                'message' => 'User Inactive Data',
                'data' => $activationData
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
