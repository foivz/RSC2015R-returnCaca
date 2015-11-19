<?php

namespace AppBundle\Service;

class GcmService
{

    public function sendNotification($regIds, $msg)
    {
        // API access key from Google API's Console
        $key = 'AIzaSyACo1LB8nOCpNJFj26v8MnpaE5xR1jCGBw';
        $registrationIds = $regIds;

        $fields = array
        (
            'registration_ids' => $registrationIds,
            'data'             => $msg
        );

        $headers = array
        (
            'Authorization: key=' . $key,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}