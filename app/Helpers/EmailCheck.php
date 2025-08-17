<?php

namespace App\Helpers;

class EmailCheck
{
    public static function check_user_email($email)
    {
        $setting = Helper::get_email_setting();
        if ($setting->check_email_status == 1) {
            $response = EmailCheck::verify_user_email($email);
            if (!empty($response)) {
                if ($response->score > 3 && $response->status == 'deliverable') {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return true;
        }

    }

    public static function verify_user_email($user_email)
    {
        $setting = Helper::get_email_setting();

        $url = 'https://api.alfredknows.com/api/v1/email-check';
        $apiKey = $setting->check_email_api_key;
        $email = $user_email;
        $data = array('email' => $email);
        $data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: ' . $apiKey,
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);
        // Do something with the response
        return $response;
    }

    public static function check_assign_cridit()
    {
        $setting = Helper::get_email_setting();
        $url = 'https://api.alfredknows.com/api/v1/credits-check';
        if ($setting->check_email_status == 1) {
            $apiKey = $setting->check_email_api_key;
        } else {
            $apiKey = '';
        }


        // Initialize cURL
        $curl = curl_init();

        // Set the API endpoint U

        // Set the API key header
        $headers = array(
            'Authorization: ' . $apiKey
        );

        // Set the cURL options
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        // Execute the cURL request
        $response = curl_exec($curl);

        // Check for errors
        if (curl_errno($curl)) {
            echo 'Error: ' . curl_error($curl);
        }

        // Close the cURL session
        curl_close($curl);

        // Process the response data
        $response = json_decode($response);

        return $response;

    }
}
