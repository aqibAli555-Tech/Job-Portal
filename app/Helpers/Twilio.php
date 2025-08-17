<?php

namespace App\Helpers;
use Twilio\Rest\Client;

class Twilio
{
    public function isPhoneNumberValid($phoneNumber)
    {
        try {
            $sid = '';
            $token = '';

            $twilio = new Client($sid, $token);

            $numberInfo = $twilio->lookups->v1->phoneNumbers($phoneNumber)
                ->fetch(["type" => ["carrier"]]);
            return [
                'valid' => true,
                'number' => $numberInfo->phoneNumber,
                'carrier_type' => $numberInfo->carrier['type'],
            ];
        } catch (\Exception $e) {
            return [
                'valid' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function sendSMS($to, $message)
    {   
        $sid = '';
        $token = '';
        // $from = '+18566992145';
        $from = '+14155238886';
        $result = $this->isPhoneNumberValid($to);
        
        if ($result['valid']) {

            $client = new Client($sid, $token);

            try {
                $client->messages->create($to, [
                    'from' => 'whatsapp:' . $from,
                    'body' => $message,
                ]);
                return [
                    'status' => true,
                    'message' => 'Message Send Successfully',
                ];

            } catch (\Exception $e) {
                return [
                    'status' => false,
                    'message' => $e->getMessage(),
                ];
            }
        }else{
            return [
                'status' => false,
                'message' => $result['error'],
            ];
        }
    }

}