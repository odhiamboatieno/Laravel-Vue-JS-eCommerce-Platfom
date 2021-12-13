<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PwaSettingProvider extends ServiceProvider
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

        // if (Schema::hasTable('pwa_settings')) {

        //     $setting      = PwaSetting::orderBy('id', 'desc')->first();
        //     $shop_setting = ShopSetting::orderBy('id', 'desc')->first();
        //     \Config::set('laravelpwa.manifest.name', $setting->app_name);
        //     \Config::set('laravelpwa.manifest.short_name', $setting->app_short_name);
        //     \Config::set('laravelpwa.manifest.theme_color', $shop_setting->theme_color);
        //     \Config::set('laravelpwa.manifest.status_bar', $shop_setting->theme_color);
        //     \Config::set('laravelpwa.manifest.icons', [
        //         '72x72'   => [
        //             'path'    => url('images/icons/icon-72x72.png'),
        //             'purpose' => 'any',
        //         ],
        //         '96x96'   => [
        //             'path'    => url('images/icons/icon-96x96.png'),
        //             'purpose' => 'any',
        //         ],
        //         '128x128' => [
        //             'path'    => url('images/icons/icon-128x128.png'),
        //             'purpose' => 'any',
        //         ],
        //         '144x144' => [
        //             'path'    => url('images/icons/icon-144x144.png'),
        //             'purpose' => 'any',
        //         ],
        //         '152x152' => [
        //             'path'    => url('images/icons/icon-152x152.png'),
        //             'purpose' => 'any',
        //         ],
        //         '192x192' => [
        //             'path'    => url('images/icons/icon-192x192.png'),
        //             'purpose' => 'any',
        //         ],
        //         '384x384' => [
        //             'path'    => url('images/icons/icon-384x384.png'),
        //             'purpose' => 'any',
        //         ],
        //         '512x512' => [
        //             'path'    => url('images/icons/icon-512x512.png'),
        //             'purpose' => 'any',
        //         ],
        //     ]);

        //     \Config::set('laravelpwa.manifest.splash', [
        //         '640x1136'  => url('images/icons/splash-640x1136.png'),
        //         '750x1334'  => url('/images/icons/splash-750x1334.png'),
        //         '828x1792'  => url('/images/icons/splash-828x1792.png'),
        //         '1125x2436' => url('/images/icons/splash-1125x2436.png'),
        //         '1242x2208' => url('/images/icons/splash-1242x2208.png'),
        //         '1242x2688' => url('/images/icons/splash-1242x2688.png'),
        //         '1536x2048' => url('/images/icons/splash-1536x2048.png'),
        //         '1668x2224' => url('/images/icons/splash-1668x2224.png'),
        //         '1668x2388' => url('/images/icons/splash-1668x2388.png'),
        //         '2048x2732' => url('/images/icons/splash-2048x2732.png'),
        //     ]);

        // }

    }
}
