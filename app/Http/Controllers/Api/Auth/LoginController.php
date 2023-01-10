<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class LoginController extends Controller
{

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
                        ], 404);
                    }
                    $user_login = User::findOrFail($user->id);
                    $user_login->is_login = 1;
                    $user_login->save();

                    $userIsLogin = User::with('profiles')->first();

                    return response()->json([
                        'success' => true,
                        'message' => 'Login Success!',
                        'data'    => $userIsLogin,
                        'token'   => $user->createToken('authToken')->accessToken
                    ]);
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
