<?php

namespace App\Helpers;

use App\Models\EmailQueue;
use App\Models\EmailSetting;
use App\Models\MailgunLog;
use Mailgun\Exception\HttpClientException;
use Mailgun\Mailgun;
use Session;

class MailGunHelper
{
    public static function get_email_setting()
    {
        $email_setting = EmailSetting::first();
        return $email_setting;
    }

    public static function send_mail_with_mailgun($email_data)
    {
        $email_setting = self::get_email_setting();
        if ($email_setting->status_mailgun == 1) {
            try {
                $mg = Mailgun::create($email_setting->api_key);
                $params = [
                    'from' => '',
                    'to' => $email_data->to,
                    'subject' => $email_data->subject,
                    'html' => $email_data->body,
                    'o:tracking-opens' => true,
                    'o:tracking-clicks' => 'https', 

                ];

                if (!empty($email_data->cc)) {
                    $params['cc'] = explode(',', $email_data->cc);
                }

                $result = $mg->messages()->send($email_setting->domain_name, $params);
                
                $id = $result->getId();
                $message = $result->getMessage();
                $statusCode = $result->getStatusCode();
                $headers = json_encode($result->getHeaders());
                if (!empty($statusCode == 200)) {
                    $email_qu['status'] = 2;
                    EmailQueue::where('id', $email_data->id)->update($email_qu);
                    MailgunLog::create([
                        'email_id' => $email_data->id,
                        'message' => $message,
                        'statuscode' => $statusCode,
                        'mailgun_email_id' => $id,
                        'headers' => $headers
                    ]);
                    return true;
                } else {
                    return false;
                }

            } catch (HttpClientException $e) {
                echo "Error sending message: " . $e->getMessage();
            }
        }
    }

    public static function get_email_status($message_id)
    {
        $email_setting = self::get_email_setting();
        $mg = Mailgun::create($email_setting->api_key);
        $messageId = $message_id;
        $response = $mg->events()->get($email_setting->domain_name, [
            'event' => 'delivered',
            'message-id' => $messageId, // Filter by the specific message ID
            'limit' => 1
        ]);
        return $response;
    }

    public static function get_stats()
    {
        $email_setting = self::get_email_setting();
        if ($email_setting->status_mailgun == 1) {

            $mg = Mailgun::create($email_setting->api_key);
            $domain = $email_setting->domain_name;
            $response = $mg->stats()->total($domain, [
                'duration' => '30d',
                'event' => ['accepted', 'delivered', 'failed', 'opened', 'clicked',]
            ]);
            return $response;
//            $data['start_date'] = $response->getStart();
            $data['end_date'] = $response->getEnd();
            $data['resolution'] = $response->getResolution();
            foreach ($response->getStats() as $item) {

                dd($item->getFailed()['permanent']['total']);
            }
        }
    }

    public static function get_total_remaning_mails()
    {
        $email_setting = self::get_email_setting();
        if ($email_setting->status_mailgun == 1) {

            $mg = Mailgun::create($email_setting->api_key);
            $domain = $email_setting->domain_name;

            $response = $mg->stats()->total($domain);
            dd($response);
        }

    }
    public static function getBounces()
    {
        $email_setting = self::get_email_setting();
        $query = [
            "limit" => "150",
            'event' => 'failed',
            "ascending" => "no"
        ];
        $email_setting = self::get_email_setting();
        $domain = $email_setting->domain_name;
        $apiKey = $email_setting->api_key;
        $mg = Mailgun::create($apiKey);
        $response = $mg->events()->get("$domain", $query);
        return $response;
    }
}
