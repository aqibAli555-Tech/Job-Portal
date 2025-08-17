<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

class UserActivated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        if (!empty($this->user->email)) {
            return ['mail'];
        } else {
            if (config('settings.sms.driver') == 'twilio') {
                return [TwilioChannel::class];
            }

            return ['nexmo'];
        }
    }

    public function toMail($notifiable)
    {
//        return true;
        return (new MailMessage)
            ->subject(trans('mail.user_activated_title', ['appName' => config('app.name'), 'userName' => $this->user->name]))
            ->greeting(trans('mail.user_activated_content_1', ['appName' => config('app.name'), 'userName' => $this->user->name]))
            ->line(trans('mail.user_activated_content_2'))
            ->line(trans('mail.user_activated_content_3', ['appName' => config('app.name')]))
            ->line(trans('mail.user_activated_content_4', ['appName' => config('app.name')]))
            ->salutation(trans('mail.footer_salutation', ['appName' => config('app.name')]));
    }

    public function toNexmo($notifiable)
    {
        return (new NexmoMessage())->content($this->smsMessage())->unicode();
    }

    protected function smsMessage()
    {
        return trans('sms.user_activated_content', ['appName' => config('app.name'), 'userName' => $this->user->name]);
    }

    public function toTwilio($notifiable)
    {
        return (new TwilioSmsMessage())->content($this->smsMessage());
    }
}
