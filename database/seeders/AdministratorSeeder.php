<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrator = new User;
        $administrator->name = "admin D&N";
        $administrator->email = "dntourtr@dntourtravel.com";
        $administrator->phone = "6288222668778";
        $administrator->photo = NULL;
        $administrator->password = Hash::make("123654Bismillah");
        $administrator->roles = json_encode(["ADMIN"]);
        $administrator->status = "ACTIVE";
        $administrator->is_login = 0;
        $administrator->save();
        $administrator_profile = new Profile;
        $administrator_profile->username = trim(preg_replace('/\s+/', '_', $administrator->name));
        $administrator_profile->address = 'Jl. Hercules Komplek Melong Green Garder';
        $administrator_profile->city = 'Cimahi Selatan';
        $administrator_profile->district = 'Melong';
        $administrator_profile->province = 'Jawa Barat';
        $administrator_profile->post_code = '40534';
        $administrator_profile->save();
        $administrator->profiles()->sync($administrator_profile->id);
        $this->command->info("User admin created successfully");
    }
}
