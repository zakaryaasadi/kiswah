<?php

namespace App\Services;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class ExpoNotification
{

    public static function sendNotification(String $notification_token, String $title, String $message, array $data)
    {
        $client = new Client([
            'headers' => [
                'Host' => 'exp.host',
                'Accept' => 'application/json',
                'Accept-Encoding' => 'gzip, deflate',
                'Content-Type' => 'application/json'
            ]
        ]);

        try {
            $response = $client->post('https://exp.host/--/api/v2/push/send', [
                'form_params' => [
                    'to' => $notification_token,
                    'sound' => 'default',
                    'title' => $title,
                    'body' => $message,
                    'data' => $data
                ]
            ]);
        } catch (ClientException $e) {
            //throw $th;
            $response = $e->getResponse();
        }    
        return json_decode($response->getBody(), true);
    }
}
