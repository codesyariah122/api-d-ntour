<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Passport;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\User;

class LoginController extends Controller
{

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
                                'message' => "Sorry, this account is already login at {$last_login} a go",
                                'quote' => 'Please check the notification again!'
                            ]);
                        }
                        $token = $user->createToken('authToken')->accessToken;

                        $user_login = User::findOrFail($user->id);
                        $user_login->is_login = 1;

                        if ($request->remember_me) {
                            $user_login->expires_at = now()->addRealDays(1);
                        }
                        $user_login->expires_at = now()->addRealMinutes(60);
                        $user_login->last_login = now();
                        $user_login->remember_token = Str::random(32);
                        $user_login->save();

                        $userIsLogin = User::whereId($user_login->id)->with('profiles')->get();

                        return response()->json([
                            'success' => true,
                            'message' => 'Login Success!',
                            'data'    => $userIsLogin,
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
