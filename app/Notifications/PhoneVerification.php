<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\TwilioChannel;
use Twilio\Rest\Client;

class PhoneVerification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $entity;
    protected $entityRef;
    protected $tokenUrl;

    public function __construct($entity, $entityRef)
    {
        $this->entity = $entity;
        $this->entityRef = $entityRef;


        // Get the Token verification URL
        $this->tokenUrl = (isset($entityRef['slug'])) ? url('verify/' . $entityRef['slug'] . '/phone') : '';
    }

    public function via($notifiable)
    {
        if (!isset($this->entityRef['name'])) {
            return false;
        }

        if (config('settings.sms.driver') == 'twilio') {
            return [TwilioChannel::class];
        }

        return ['nexmo'];
    }

    public function toNexmo($notifiable)
    {
        return (new NexmoMessage())->content($this->smsMessage())->unicode();
    }

    protected function smsMessage()
    {


        $sid = '';
        $token = '';


        $twilioCreate = new Client($sid, $token);
        $service = $twilioCreate->verify->v2->services
            ->create("Hungry For Jobs");

        $twilioSend = new Client($sid, $token);
        $verification = $twilioSend->verify->v2->services($service->sid)
            ->verifications
            ->create($this->entity->whatsapp_number, // to
                "whatsapp", // channel
                [
                    "TemplateSid" => "",
                    "locale" => 'en',
                ]
            );
        $user = User::withoutGlobalScopes()->where('phone', $this->entity->phone)->first();
        $user->sid = $verification->serviceSid;
        $user->save();
        return true;
    }

    public function toTwilio($notifiable)
    {
        $this->smsMessage();
    }
}
