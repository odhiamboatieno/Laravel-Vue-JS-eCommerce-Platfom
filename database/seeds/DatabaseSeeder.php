
<?php

use Database\Seeders\CurrencySeeder;
use Database\Seeders\MenuPermission;
use Database\Seeders\MenuSeeder;
use Database\Seeders\PaymentSettingSeeder;
use Database\Seeders\SocialSeed;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        // role seed

        DB::table('roles')->insert([
            'role_name' => 'Super Admin',
        ]);

        //  menu seeding

        $this->call(MenuSeeder::class);

        // admin seed
        DB::table('admins')->insert([
            'name'     => 'admin',
            'email'    => 'admin@swissgroce.com',
            'avatar'   => 'default_avatar.png',
            'role_id'  => 1,
            'password' => Hash::make('123456'),
        ]);

        // admin menu permission
        $this->call(MenuPermission::class);
        // facebook page messanger setting

        DB::insert("INSERT INTO `messengers` (`id`, `app_id`, `status`, `created_at`, `updated_at`) VALUES
        (1, '147855768574821', 1, NULL, '2020-08-10 22:21:24')");

        // payment setting

        $this->call(PaymentSettingSeeder::class);
        //  seo settings

        DB::insert("INSERT INTO `seo_settings` (`id`, `title`, `meta_image`, `sitemap_link`, `keyword`, `author`, `description`, `created_at`, `updated_at`) VALUES
        (1, 'Limmerz | Start Your Own Ecommerce Today', '5ed50b0000acd.jpeg', 'sitemap.com', 'Limmex Groce, grocery, ecommerce, home applience, lacommerz, lacommerz, La commerz, La commerz, Limmex ecommerce, Limmex ecommerce, Limmex Automation, Limmex Automation, limmerz', 'Limmex Automation',
        'Limmerz is an ecommerce solution for both grocery and lifestyle based on laravel and vue.js', '2020-02-09 00:05:09', '2020-06-01 08:04:49')");

        //  shipping cost setting

        DB::insert("INSERT INTO `shipping_costs` (`id`, `shipping_amount`, `minimum_order_amount`, `order_amount`, `discount_amount`, `shipping_status`, `discount_status`, `created_at`, `updated_at`) VALUES
       (1, 40, 300, 1500, 20, 1, 1, '2020-04-18 20:48:42', '2020-05-18 06:32:41')");

        //    shop settings

        DB::insert("INSERT INTO `shop_settings` (`id`, `shop_name`, `shop_short_name`, `address`, `footer_text`, `phone`, `email`, `facebook`, `twitter`, `youtube`, `logo_header`, `logo_footer`, `favicon`, `theme_color`, `hot_deal_status`, `slider_status`, `onsale_status`,`sidemenu_status`, `created_at`, `updated_at`) VALUES
      (1, 'Limmerz', NULL, '219 muktobangla complex , Dhaka-1205', '2020 all right reserve by@LimmexAutomation', '01312447767', 'business@limmexbd.com', 'https://facebook.com', 'https://twitter.com', 'https://youtube.com', '5ed50bc056bf0.png', '5ed50bc05fd25.png', '5ed50bc066add.png', '#E3106E', 1, 0, 1,1,'2020-02-10 03:00:06', '2020-07-15 09:40:09')");

        // social login setting

        $this->call(SocialSeed::class);

        // Email setting
        DB::insert("INSERT INTO `emails` (`id`, `driver`, `host`, `port`, `username`, `password`, `encryption`, `from_address`, `from_name`, `status`, `created_at`, `updated_at`) VALUES (1, 'smtp', 'mailtrap.io', '587', 'limmexbd@yahooo.com', 'your_password', 'tls', 'noreply@yourgmail.com', 'Fresh', '1', NULL, NULL);");

        //  currency
        $this->call(CurrencySeeder::class);
        // insert trial setting
        DB::table('trials')->insert([
            'product_in_cart'       => 1,
            'max_trial_item'        => 1,
            'trial_charge_per_item' => 0,
            'status'                => 0,
        ]);

        // insert default date_slot  setting
        DB::insert("INSERT INTO `delivery_slot_settings`
        (`id`, `date_interval`, `date_end`, `status`, `created_at`, `updated_at`)
         VALUES (NULL, '0', '8', '1', NULL, NULL)");

        //  google analytics seeder

        DB::insert("INSERT INTO `google_analytics` (`id`, `app_id`, `status`, `created_at`, `updated_at`) VALUES (NULL, 'UA-1214234234', '0', NULL, NULL)");

    }
}
