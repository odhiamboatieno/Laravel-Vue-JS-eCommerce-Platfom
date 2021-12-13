<?php

namespace App\Providers;
use Config;
use DB;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
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
        if (\Schema::hasTable('emails')) {
            $mail = DB::table('emails')->first();
            if ($mail) //checking if table is not empty
            {
                $config = array(
                    'driver'     => $mail->driver,
                    'host'       => $mail->host,
                    'port'       => $mail->port,
                    'from'       => array('address' => $mail->from_address ? $mail->from_address : 'noreply@email.com', 'name' => $mail->from_name ? $mail->from_name : 'Example'),
                    'encryption' => $mail->encryption,
                    'username'   => $mail->username,
                    'password'   => $mail->password,
                    // 'sendmail'   => '/usr/sbin/sendmail -bs',
                    // 'pretend'    => false,
                );
                Config::set('mail', $config);
            }
        }
    }
}
