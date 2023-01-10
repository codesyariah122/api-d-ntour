<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Helpers\UserHelpers;
use App\Models\ApiKeys;
use App\Models\Contact;
use App\Mail\ContactEmail;
use App\Mail\ReplyContactEmail;

class DnTourTravelController extends Controller
{
    private $base_url, $get_helper;

    public function __construct()
    {
        $this->base_url = env('DNTOUR_GEO_API');
        $this->get_helper = new UserHelpers;
    }

    public function get_your_ip()
    {
        $ip_addr = $this->get_helper->getIpAddr();
        return response()->json([
            'message' => $ip_addr === '127.0.0.1' ? 'oops !! sory you are access from local' : 'Detected your ip address !',
            'data' => $ip_addr === '127.0.0.1' ? null : $ip_addr
        ]);
    }

    public function geo_location(Request $request)
    {
        try {
            $env_token = env('DNTOUR_API_TOKEN');
            $api_token = ApiKeys::where('token', $env_token)->first();

            $ip_addr = $this->get_helper->getIpAddr();

            $api_auth = [
                'apiToken' => $request->apiToken,
                'ip' => $ip_addr === '127.0.0.1' ? $request->ip : $ip_addr
            ];

            if ($api_auth['apiToken'] === '' || $api_auth['apiToken'] === null) {
                $data_response = [
                    'message' => 'Access danied, please request token!',
                    'status' => 401,
                    'data' => null
                ];
                return response()->json([
                    'message' => $data_response['message'],
                    'data' => $data_response['data']
                ], $data_response['status']);
            }



            if ($api_token['token'] && $api_token['token'] === $api_auth['apiToken']) {
                $context_api = [
                    'geo_url' => env('DNTOUR_GEO_API'),
                    'geo_key' => env('DNTOUR_GEO_KEY'),
                    'dntour_token' => $api_token['token']
                ];


                $endPoint = "{$this->base_url}?apiKey={$context_api['geo_key']}&ip={$api_auth['ip']}";

                $response = Http::get($endPoint)->json();

                $data_response = [
                    'message' => 'Geo location api',
                    'status' => 200,
                    'data' => $response
                ];
                return response()->json([
                    'message' => $data_response['message'],
                    'data' => $data_response['data']
                ], $data_response['status']);
            } else {
                $data_response = [
                    'message' => 'Sory your token is not valid!',
                    'status' => 401,
                    'data' => null
                ];
                return response()->json([
                    'message' => $data_response['message'],
                    'data' => $data_response['data']
                ], $data_response['status']);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function sending_message(Request $request)
    {
        try {
            $env_token = env('DNTOUR_API_TOKEN');
            $api_token = ApiKeys::where('token', $env_token)->first();

            // geo location detector
            $ip_addr = $this->get_helper->getIpAddr();

            $api_auth = [
                'apiToken' => $request->apiToken,
                'ip' => $ip_addr === '127.0.0.1' ? $request->ip : $ip_addr
            ];
            $context_api = [
                'geo_url' => env('DNTOUR_GEO_API'),
                'geo_key' => env('DNTOUR_GEO_KEY'),
                'dntour_token' => $api_token['token']
            ];
            $endPoint = "{$this->base_url}?apiKey={$context_api['geo_key']}&ip={$api_auth['ip']}";

            $response = Http::get($endPoint)->json();

            if ($api_token->token !== $request->apiToken) {
                return response()->json([
                    'message' => 'Sory your token is not valid!'
                ], 402);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required',
                'email' => 'required|email',
                'subject' => 'required',
                'message' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $data_geo = [
                'city' => $response['city'],
                'district' => $response['district'],
                'longitude' => $response['longitude'],
                'latitude' => $response['latitude']
            ];

            $new_message = new Contact;
            $new_message->name = strip_tags($request->name);
            $new_message->phone = $this->get_helper->formatPhoneNumber($request->phone);
            $new_message->email = strip_tags($request->email);
            $new_message->city = $data_geo['city'];
            $new_message->district = $data_geo['district'];
            $new_message->longitude = $data_geo['longitude'];
            $new_message->latitude = $data_geo['latitude'];
            $new_message->subject = strip_tags($request->subject);
            $new_message->message = htmlspecialchars($request->message);
            $new_message->save();

            $admin = $this->get_helper->adminEmail();

            $role = json_decode($admin->roles)[0];
            $details = [
                'title' => "Pesan baru dari " . env('APP_BRAND'),
                'url' => env('FRONT_END'),
                'roles' => $role,
                'route' => $role,
                'name' => $new_message->name,
                'email' => $new_message->email,
                'phone' => $new_message->phone,
                'message' => $new_message->message
            ];

            Mail::to($admin->email)->send(new ContactEmail($details));
            Mail::to($new_message->email)->send(new ReplyContactEmail($details));

            return response()->json([
                'success' => true,
                'message' => $new_message->name . ' baru saja mengirim pesan',
                'data' => $new_message
            ], 202);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
