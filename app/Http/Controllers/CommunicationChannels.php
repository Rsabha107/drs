<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommunicationChannels extends Controller
{
    //
    public function sendWhatsapp()
    {

        $phone = '97474428989';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/v15.0/245861265276216/messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "messaging_product": "whatsapp",
                "to": ' . $phone . ',
                "type": "template",
                "template": {
                    "name": "task_number",
                    "language": {
                        "code": "en_US"
                    }
                }
            }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer EAAXkEZCWCuLQBO73niu3xjqnQ1kwb9cDU9SiFZCEhZAtfJkVQZAsXQMqaARiBv6ZB7l2oe8wbmtloZA2tdoZCQw09yZCNUBb6Mx1BWnpNNZArs2Q0HJiZAro7Cri0k7HFOOAqsuu1xyECAevWhbPvCQ7co2eqm3k5fpEVXHumcEHXThbMsHvEZB4kCTR7inlf80TrqVedeCeDlqn9hfnWOG',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
}
