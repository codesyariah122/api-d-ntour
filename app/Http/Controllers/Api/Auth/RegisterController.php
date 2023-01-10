<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Profile;
use App\Models\UserActivation;
use App\Helpers\UserHelpers;
use App\Mail\EmailActivation;

class RegisterController extends Controller
{
    private $email_domain;

    public function __construct()
    {
        $this->email_domain = new UserHelpers;
    }
    public function register(Request $request)
    {
        try {
            $helper = new UserHelpers;

            $validator = Validator::make($request->all(), [
                'name'      => 'required',
                'email'     => 'required|email|unique:users',
                'password'  => [
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

            $user = new User;
            $user->name = strip_tags($request->name);
            $user->email = strip_tags($request->email);
            $user->phone = $request->phone ? $helper->formatPhoneNumber($request->phone) : null;
            $user->password = Hash::make($request->password);
            $user->roles = json_encode($request->roles ? $request->roles : ['CUSTOMER']);
            $user->status = 'INACTIVE';
            $user->is_login = 0;
            $user->save();

            // saving profile user table
            $user_profile = new Profile;
            $user_profile->username = trim(preg_replace('/\s+/', '_', $user->name));
            if ($request->file('photo')) {
                $file = $request->file('photo')->store(trim(preg_replace('/\s+/', '', $user->name)) . '/image/profile', 'public');
                $user_profile->photo = $file;
            }
            $user_profile->about = $request->about ? $request->about : null;
            $user_profile->save();
            $profile_id = $user_profile->id;
            $user->profiles()->sync($profile_id);

            // saving user activation table
            $user_activation = new UserActivation;
            $user_activation->token = Str::random(11);
            $user_activation->save();
            $activation_id = $user_activation->id;
            $user->user_activations()->sync($activation_id);


            // sending email to user activation
            $details = [
                'title' => 'Kamu Telah Berhasil Registrasi Di Website D & N Tour Travel',
                'url' => 'https://dntourtravel.com',
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone,
                'token' => $user_activation->token
            ];

            Mail::to($user->email)->send(new EmailActivation($details));

            $new_user = User::findOrFail($user->id);
            $new_user->activation_id = $user_activation->token;
            $new_user->save();

            $domainEmail = $this->email_domain->customerDomainEmail($user);
            return response()->json([
                'success' => true,
                'message' => "Halo {$user->name}, registrasi kamu berhasil, silahkan cek inbox <a href='https://{$domainEmail}' target='_blank' class='btn btn-link'>{$user->email}</a> untuk mengaktifkan akun kamu.",
                'data'    => $new_user,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function activation(Request $request, $id)
    {
        try {
            $user_activation = User::findOrFail($id);
            $user_activation->status = 'ACTIVE';
            $user_activation->activation_id = null;
            $user_activation->save();
            $new_user_active = User::findOrFail($id);
            return response()->json([
                'message' => "Hallo, {$new_user_active->name}, akun kamu berhasil diaktivasi!",
                'data' => $new_user_active
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
