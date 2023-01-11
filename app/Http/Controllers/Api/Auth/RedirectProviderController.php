<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Helpers\UserHelpers;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Profile;
use App\Models\UserActivation;

class RedirectProviderController extends Controller
{
    public const PROVIDERS = ['google'];
    public const SUCCESS = 200;
    public const FORBIDDEN = 403;
    public const UNAUTHORIZED = 401;
    public const NOT_FOUND = 404;
    public const NOT_ALLOWED = 405;
    public const UNPROCESSABLE = 422;
    public const SERVER_ERROR = 500;
    public const BAD_REQUEST = 400;
    public const VALIDATION_ERROR = 252;
    private $helper;

    public function __construct()
    {
        $this->helper = new UserHelpers;
    }

    public function sendResponse($result = [], $message = NULL)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, self::SUCCESS);
    }

    /**
     * success response method.
     *
     * @param  str  $message
     * @return \Illuminate\Http\Response
     */
    public function respondWithMessage($message = NULL)
    {
        return response()->json(['success' => true, 'message' => $message], self::SUCCESS);
    }

    /**
     * error response method.
     *
     * @param  int  $code
     * @param  str  $error
     * @param  array  $errorMessages
     * @return \Illuminate\Http\Response
     */
    public function sendError($code = NULL, $error = NULL, $errorMessages = [])
    {
        $response['success'] = false;

        switch ($code) {
            case self::UNAUTHORIZED:
                $response['message'] = 'Unauthorized';
                break;
            case self::FORBIDDEN:
                $response['message'] = 'Forbidden';
                break;
            case self::NOT_FOUND:
                $response['message'] = 'Not Found.';
                break;
            case self::NOT_ALLOWED:
                $response['message'] = 'Method Not Allowed.';
                break;
            case self::BAD_REQUEST:
                $response['message'] = 'Bad Request.';
                break;
            case self::UNPROCESSABLE:
                $response['message'] = 'Unprocessable Entity.';
                break;
            case self::SERVER_ERROR:
                $response['message'] = 'Whoops, looks like something went wrong.';
                break;
            case self::VALIDATION_ERROR:
                $response['message'] = 'Validation Error.';
                break;
            default:
                $response['message'] = 'Whoops, looks like something went wrong.';
                break;
        }

        $response['message'] = $error ? $error : $response['message'];
        if (!empty($errorMessages)) {
            $response['errors'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
    private function respondWithToken($token)
    {
        $success['token'] =  $token;
        $success['access_type'] = 'bearer';
        $success['expires_in'] = now()->addDays(15);

        return $this->sendResponse($success, 'Login successfully.');
    }

    public function redirectToProvider($provider)
    {
        if (!in_array($provider, self::PROVIDERS)) {
            return $this->sendError(self::NOT_FOUND);
        }

        $success['provider_redirect'] = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();

        return $this->sendResponse($success, "Provider '" . $provider . "' redirect url.");
    }


    public function handleProviderCallback($provider)
    {

        if (!in_array($provider, self::PROVIDERS)) {
            return $this->sendError(self::NOT_FOUND);
        }

        try {
            $providerUser = Socialite::driver($provider)->stateless()->user();

            if ($providerUser) {

                $user = User::where('provider_name', $provider)
                    ->where('google_id', $providerUser->getId())
                    ->first();

                $big_data_key = env('API_KEY_BIG_DATA');
                $api_ip_key = env('API_KEY_IP_API');

                $ip_address = $this->helper->getIpAddr();

                if ($ip_address === "172.19.0.1" || $ip_address === "127.0.0.1") {
                    $ip_address = "103.139.10.159";
                } else {
                    $ip_address = $ip_address;
                }

                $userDetect = Http::get("https://api.bigdatacloud.net/data/timezone-by-ip?ip={$ip_address}&key={$big_data_key}")->json();
                $current = Carbon::now()->setTimezone($userDetect['ianaTimeId']);

                $locator = Http::get("http://api.ipapi.com/{$ip_address}?access_key={$api_ip_key}")->json();

                if (!$user) {
                    $newuser = new User;
                    $newuser->google_id = $providerUser->getId();
                    $newuser->provider_name = $provider;
                    $newuser->name = $providerUser->getName();
                    $newuser->email = $providerUser->getEmail();
                    $newuser->password = Hash::make($providerUser->getName() . '@' . $providerUser->getId());
                    $newuser->roles = json_encode(['CUSTOMER']);
                    $newuser->status = 'ACTIVE';
                    $newuser->is_login = 1;
                    $newuser->expires_at = now()->addRealDays(1);
                    $newuser->last_login = $current;
                    $newuser->save();
                    // saving profile table
                    $profile = new Profile;
                    $profile->username = trim(preg_replace('/\s+/', '_', strtolower($providerUser->name())));
                    $profile->g_avatar = $providerUser->getAvatar();
                    $profile->longitude = $locator['longitude'];
                    $profile->latitude = $locator['latitude'];
                    $profile->post_code = $locator['zip'];
                    $profile->save();
                    $profile_id = $profile->id;
                    $newuser->profiles()->sync($profile_id);

                    $token = $newuser->createToken('authToken')->accessToken;

                    // saving user activation table
                    $user_activation = new UserActivation;
                    $user_activation->token = Str::random(11);
                    $user_activation->save();
                    $activation_id = $user_activation->id;
                    $newuser->user_activations()->sync($activation_id);

                    // return $this->respondWithToken($token);
                    return redirect(env('FRONTEND_APP_TEST') . '/auth/success?access_token=' . $token);
                }


                $user->is_login = 1;
                $user->last_login = $current;
                $user->save();

                $user_profile_data = User::with('profiles')->findOrFail($user->id);

                $profile = Profile::find($user_profile_data['profiles'][0]['id'])->first();

                $profile->longitude = $locator['longitude'];
                $profile->latitude = $locator['latitude'];
                $profile->post_code = $locator['zip'];
                $profile->save();

                $token = $user->createToken(env('apiToken'))->accessToken;

                // return $this->respondWithToken($token);
                return redirect(env('FRONTEND_APP_TEST') . '/auth/success?access_token=' . $token);
            }
        } catch (\Exception $e) {
            return $this->sendError(self::UNAUTHORIZED, null, ['error' => $e->getMessage()]);
        }
    }
}
