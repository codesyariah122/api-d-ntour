<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Helpers\UserHelpers;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $userDetect = Http::get("https://api.bigdatacloud.net/data/timezone-by-ip?ip={$get_ipaddr}&key={$big_data_key}")->json();

        // $current = Carbon::now()->setTimezone($userDetect['ianaTimeId']);
        // $expires_date = Carbon::now()->addRealDays(1)->setTimezone($userDetect['ianaTimeId']);

        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
    }
}
