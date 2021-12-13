<?php

namespace App\Sms;

/**
 *
 */
class SslSms
{

//   sending ssl wirless   sms from this file
    public static function send($number, $message)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => "https://smsplus.sslwireless.com/api/v3/send-sms?api_token=Bajartime-572d2c1c-dc07-4cc3-897a-e746sffd956ff45d%20&sid=BAJARTIMENON&sms=" . urlencode($message) . "&msisdn=" . $number . "&csms_id=1929393",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // echo $response;

    }
}
