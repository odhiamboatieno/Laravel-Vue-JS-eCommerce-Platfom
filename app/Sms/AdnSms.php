<?php

namespace App\Sms;

/**
 *
 */
class AdnSms
{

//   sending adn digital sms from this file
    public static function send($number, $message, $type = 'TEXT')
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => "https://portal.adnsms.com/api/v1/secure/send-sms?api_key=KEY-hyq51f9o0wsd8m85n6s027q1hbrrz3wr1k&api_secret=WbQ7tcjaKTP@CLsdsd82&request_type=SINGLE_SMS&message_type=$type&mobile=$number&message_body=" . urlencode($message) . "",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}
