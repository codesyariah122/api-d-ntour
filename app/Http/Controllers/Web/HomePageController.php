<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class HomePageController extends Controller
{
    public function index()
    {
        $context = [
            'brand' => env('APP_BRAND'),
            'hero' => [
                'logo' => asset('images/web-asset/logo/D_N-Logo.png'),
                'image' => asset('images/web-asset/artwork-store.jpg'),
                'title' => 'D & N',
                'span' => 'Tour Travel',
                'quote' => 'Travel Bandung - Bandara(Soetta)'
            ],
            'login' => [
                'image' => asset('images/web-asset/new-hero-mobile2.jpg'),
            ]
        ];
        return view('home.app')->with('context', $context);
    }
}