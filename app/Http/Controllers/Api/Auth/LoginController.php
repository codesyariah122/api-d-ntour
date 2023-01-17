<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Passport;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\PasswordReset;
use App\Helpers\UserHelpers;

class LoginController extends Controller
{
    private $helper;

    public function __construct()
    {
        $this->helper = new UserHelpers;
    }

    private function forbidenIsUserLogin($isLogin)
    {
        return $isLogin ? true : false;
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $user = User::where('email', $request->email)->first();

            $big_data_key = env('API_KEY_BIG_DATA');
            $ip_address = $this->helper->getIpAddr();
            $get_ipaddr = $ip_address === '127.0.0.1' ? '103.139.10.39' : $ip_address;

            $userDetect = Http::get("https://api.bigdatacloud.net/data/timezone-by-ip?ip={$get_ipaddr}&key={$big_data_key}")->json();

            $current = Carbon::now()->setTimezone($userDetect['ianaTimeId']);
            $expires_date = Carbon::now()->addRealDays(1)->setTimezone($userDetect['ianaTimeId']);

            if (!$user) {
                return response()->json([
                    'not_found' => true,
                    'message' => 'Your email not registered !'
                ]);
            } else {

                if (!Hash::check($request->password, $user->password)) :
                    return response()->json([
                        'success' => false,
                        'message' => 'Your password its wrong'
                    ]);
                else :
                    if ($user->status === "INACTIVE") {
                        return response()->json([
                            'in_active' => true,
                            'message' => "{$user->name}, akun anda belum di aktivasi, silahkan aktivasi melalui link berikut.",
                            "link" => env('FRONTEND_APP') . "/activated/user/{$user->activation_id}"
                        ]);
                    } else {
                        if ($this->forbidenIsUserLogin($user->is_login)) {
                            $last_login = Carbon::parse($user->last_login)->diffForHumans();
                            return response()->json([
                                'is_login' => true,
                                'message' => "Sorry, this account is already login at {$last_login}",
                                'quote' => 'Please check the notification again!'
                            ]);
                        }

                        $checkUserIfReset = PasswordReset::whereEmail($user->email)->first();

                        if ($checkUserIfReset) {
                            return response()->json([
                                'status_reset' => 'Active',
                                'message' => 'Password reset process is in progress, please proceed to reset password',
                                'data' => $checkUserIfReset
                            ]);
                        }

                        $token = $user->createToken('authToken')->accessToken;

                        $user_login = User::findOrFail($user->id);
                        $user_login->is_login = 1;

                        if ($request->remember_me) {
                            $user_login->expires_at = Carbon::now()->addRealDays(1)->setTimezone($userDetect['ianaTimeId']);
                        }
                        $user_login->expires_at = Carbon::now()->addRealMinutes(60)->setTimezone($userDetect['ianaTimeId']);
                        $user_login->last_login = $current;
                        $user_login->remember_token = Str::random(32);
                        $user_login->save();

                        $userIsLogin = User::whereId($user_login->id)->with('profiles')->get();

                        return response()->json([
                            'success' => true,
                            'message' => 'Login Success!',
                            'data'    => $userIsLogin,
                            'remember_token' => $user_login->remember_token,
                            'token'   => $token
                        ]);
                    }
                endif;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * logout
     *
     * @param  mixed $request
     * @return void
     */
    public function logout(Request $request)
    {
        try {
            $user = User::findOrFail($request->user()->id);
            $user->is_login = 0;
            $user->expires_at = null;
            $user->remember_token = null;
            $user->save();

            $removeToken = $request->user()->tokens()->delete();

            if ($removeToken) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logout Success!',
                    'data' => $user
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function userIsLogin()
    {
        try {
            $user = User::whereIsLogin(1)->with('profiles')->first();
            return response()->json([
                'message' => 'User data is login',
                'data' => $user
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
