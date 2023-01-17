<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Illuminate\Support\Str;

class PushNotificationController extends Controller
{
    public function push_notification(Request $request)
    {
        $firebaseToken = Uuid::uuid4()->toString();
        // $firebaseToken = 'ckWX0_TN7KmP5ntW1gDPtw:APA91bFSQH_Wq2FsxonnGQfpPLDY7-D28cIhmfSFYX-6ayWPpB-7RiykE3YcvbqBWnmZkH7Otlo-p9Hg89WuoRDG8pO90ShOYIYttZdfv2uvMBGV984HPrHDaZIuZ53fy3--Rjts7E7L';
        $firebase_server_key = env('FIREBASE_SERVER_KEY');
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
                "icon" => asset('images/web-asset/favico/icon.png'),
                "content_available" => true,
                "priority" => "high",
            ]
        ];
        $dataString = json_encode($data);
        $endPoint = env('FIREBASE_FCM_ENDPOINT');

        $headers = [
            'Authorization: key=' . $firebase_server_key,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $endPoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);

        return response()->json([
            'message' => 'Fetch notification firebase',
            'data' => $response
        ]);
    }
}
