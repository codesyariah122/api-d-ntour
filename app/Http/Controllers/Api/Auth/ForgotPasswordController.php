<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\ForgotPasswordEmail;
use App\Models\User;
use App\Models\ApiKeys;
use App\Models\PasswordReset;
use App\Helpers\UserHelpers;

class ForgotPasswordController extends Controller
{
    private $helper;

    public function __construct()
    {
        $this->helper = new UserHelpers;
    }

    public function checkTokenReset($token)
    {
        $token = PasswordReset::whereToken($token)->first();
        if (!$token) {
            return response()->json([
                'valid' => false,
                'message' => 'Your token reset is not valid'
            ]);
        }

        return response()->json([
            'valid' => true,
            'message' => 'Your token is valid',
            'token' => $token
        ]);
    }

    public function forgot(Request $request, $apiToken)
    {
        try {
            $token = ApiKeys::whereToken($apiToken)->first();

            if ($apiToken !== $token->token) {
                return response()->json([
                    'error' => true,
                    'message' => 'Oops sory your token is not valid !'
                ]);
            }

            $validator = Validator::make($request->all(), [
                'email' => 'required|email'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $email = $request->email;
            $checkUser = User::whereEmail($email)->with('profiles')->first();

            if (!$checkUser) {
                return response()->json([
                    'message' => 'Ooops sory your account is not register!'
                ]);
            }

            $big_data_key = env('API_KEY_BIG_DATA');
            $ip_address = $this->helper->getIpAddr();
            $get_ipaddr = $ip_address === '127.0.0.1' ? '103.139.10.39' : $ip_address;

            $userDetect = Http::get("https://api.bigdatacloud.net/data/timezone-by-ip?ip={$get_ipaddr}&key={$big_data_key}")->json();

            $current = Carbon::now()->setTimezone($userDetect['ianaTimeId']);

            $token = Str::random(32);
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => $current
            ]);

            $details = [
                'title' => 'Reset password D & N Tour Travel',
                'url' => 'https://dntourtravel.com',
                'id' => $checkUser->id,
                'username' => $checkUser->profiles[0]->username,
                'email' => $checkUser->email,
                'token' => $token
            ];
            Mail::to($email)->send(new ForgotPasswordEmail($details));

            $tokenReset = PasswordReset::whereEmail($email)->first();

            return response()->json([
                'success' => true,
                'message' => 'Check your inbox email and follow the link to reset your password',
                'data' => $checkUser,
                'token_reset' => $tokenReset->token
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function password_reset($email)
    {
        try {
            if ($email) {
                $passwordReset = PasswordReset::whereEmail($email)->first();
                if (!$passwordReset) {
                    return response()->json([
                        'message' => 'Token is not found'
                    ]);
                }

                return response()->json([
                    'reset' => true,
                    'data' => $passwordReset
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'Please put the email!!'
                ]);
            }
        } catch (\Throwable $th) {
            throw ($th);
        }
    }

    public function reset_password(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'password' => [
                    'required', 'confirmed', Password::min(8)
                        ->mixedCase()
                        ->letters()
                        ->numbers()
                        ->symbols()
                ]

            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $token = $request->token;
            $password_reset = PasswordReset::whereToken($token)->first();
            if (!$password_reset) {
                return response()->json([
                    'error_token' => true,
                    'message' => 'Token is not valid'
                ]);
            }

            $password_update = User::whereEmail($password_reset->email)->first();

            if (!$password_reset) {
                return response()->json([
                    'not_found' => true,
                    'message' => "User does\'nt Exists"
                ]);
            }

            $password_update->password = Hash::make($request->password);
            $password_update->save();

            PasswordReset::where('token', $token)->delete();

            return response()->json([
                'success' => true,
                'message' => "OK! {$password_update->name}, password anda berhasil di update.",
                'data' => $password_update
            ]);
        } catch (\Throwable $th) {
            throw ($th);
        }
    }
}
