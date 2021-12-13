<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SocialSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::insert("INSERT INTO `social_creadentials` (`id`, `provider`, `app_id`, `app_secret`, `status`, `live_status`, `created_at`, `updated_at`) VALUES
        (1, 'facebook', '1256802924708629', '793eeb9bc876ec94526243a212fd2f2e', 1, 1, '2020-04-11 16:09:55', '2020-04-24 23:09:28'),
        (2, 'google', '500564591191-vcvu4nodvnca5c7nq6tr21fq2hhb0e11.apps.googleusercontent.com', 'FTiJAbCehKbdVWRIcDHDsG0Z', 1, 1, '2020-04-11 16:09:57', '2020-04-24 23:09:32'),
        (3, 'twitter', 'your_twitter_app_id', 'your_twitter_app_secret', 0, 0, '2020-04-11 16:10:01', '2020-06-01 08:43:41')");
    }
}
