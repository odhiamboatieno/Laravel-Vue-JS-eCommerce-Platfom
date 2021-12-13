<?php

namespace App\Providers;

use App\Model\Setting\SocialCreadential;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class CredentialServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('social_creadentials')) {
            $social_provider = SocialCreadential::where('status', '1')->get();

            $provider_array = [];

            foreach ($social_provider as $value) {

                $provider_array[$value->provider] = [
                    'client_id'     => $value->app_id,
                    'client_secret' => $value->app_secret,
                    'redirect'      => url('/') . '/' . 'login/' . $value->provider . '/callback',
                ];

            }

            $this->app['config']['services'] = $provider_array;

        }

    }
}
