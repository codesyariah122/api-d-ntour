<?php

namespace App\Helpers;


use App\Models\User;

class UserHelpers
{
    public function getIpAddr()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return request()->ip();
    }

    public function formatPhoneNumber($nohp)
    {
        // kadang ada penulisan no hp 0811 239 345
        $nohp = str_replace(" ", "", $nohp);
        // kadang ada penulisan no hp (0274) 778787
        $nohp = str_replace("(", "", $nohp);
        // kadang ada penulisan no hp (0274) 778787
        $nohp = str_replace(")", "", $nohp);
        // kadang ada penulisan no hp 0811.239.345
        $nohp = str_replace(".", "", $nohp);
        // kadang ada penulisan no hp 0811-239-345
        $nohp = str_replace("-", "", $nohp);
        // cek apakah no hp mengandung karakter + dan 0-9
        if (!preg_match('/[^+0-9]/', trim($nohp))) {
            // cek apakah no hp karakter 1-3 adalah +62
            if (substr(trim($nohp), 0, 1) == '+') {
                $hp = '' . substr(trim($nohp), 1);
            } elseif (substr(trim($nohp), 0, 2) == '62') {
                $hp = trim($nohp);
            } elseif (substr(trim($nohp), 0, 1) == '0') {
                $hp = '62' . substr(trim($nohp), 1);
            }
        }
        return $hp;
    }

    public function adminEmail()
    {
        $admin = User::where('roles', json_encode(['ADMIN']))
            ->where('email', env('MAIL_USERNAME'))
            ->where('status', 'ACTIVE')->first();
        return $admin;
    }

    public function customerDomainEmail($user)
    {
        $manipulation_email = strpos($user->email, '@');
        $email_domain = substr($user->email, $manipulation_email + 1);
        return $email_domain;
    }
}
