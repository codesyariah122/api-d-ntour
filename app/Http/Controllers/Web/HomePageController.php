<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class HomePageController extends Controller
{
    public function index()
    {
        $context = [
            'seo' => [
                'title' => env('APP_BRAND'),
                'canonical' => 'https://api.store.dntourtravel.com',
                'meta_desc' => 'Travel Bandung - Bandara(Soetta)',
                'meta_key' => 'Private charter - Drop Trip - Regular - City Tour',
                'meta_author' => 'D&N::TOUR::TRAVEL',
                'og_url' => env('APP_URL_PUBLIC'),
                'og_type' => 'website',
                'og_site_name' => env('APP_BRAND'),
                'og_title' => env('APP_NAME'),
                'og_desc' => 'Travel Bandung - Bandara(Soetta)',
                'og_image' => asset('images/web-asset/artwork-store.jpg'),
                'og_image_width' => '600',
                'og_image_height' => '590'
            ],
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
            ],
            'activate' => [
                'first_vector' => asset('images/web-asset/vector/3891942.jpg'),
                'second_vector' => asset('images/web-asset/vector/4853748.jpg')
            ],
            'forgot' => [
                'first_vector' => asset('images/web-asset/vector/forgot.jpg'),
                'second_vector' => asset('images/web-asset/vector/reset.jpg')
            ]
        ];
        return view('home.app', $context)->with('context', $context);
    }
}
