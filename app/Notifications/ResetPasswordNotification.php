<?php

namespace App\Notifications;

use App\Helpers\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $token;
    protected $field;

    public function __construct($user, $token, $field)
    {
        $this->user = $user;
        $this->token = $token;
        $this->field = $field;
    }

    public function via($notifiable)
    {
        if ($this->field == 'phone') {
            if (config('settings.sms.driver') == 'twilio') {
                return [TwilioChannel::class];
            }

            return ['nexmo'];
        } else {
            return ['mail'];
        }
    }

    public function toMail($notifiable)
    {

        $resetPwdUrl = url('password/reset/' . $this->token);
        $data['subject'] = 'Reset Your Password';
        $data['myName'] = $this->user->name;
        $data['email'] = $this->user->email;
        $data['reseturl'] = $resetPwdUrl;
        $data['header'] = 'Reset Password';
        $data['view'] = 'emails.reset_password';
        $helper = new Helper();
        $response = $helper->send_email($data);

//		return (new MailMessage)
//			->subject(trans('mail.reset_password_title'))
//			->line(trans('mail.reset_password_content_1'))
//			->line(trans('mail.reset_password_content_2'))
//			->action(trans('mail.reset_password_action'), $resetPwdUrl)
//			->line(trans('mail.reset_password_content_3'))
//			->salutation(trans('mail.footer_salutation', ['appName' => config('app.name')]));
    }

    public function toNexmo($notifiable)
    {
        return (new NexmoMessage())->content($this->smsMessage())->unicode();
    }

    protected function smsMessage()
    {
        return trans('sms.reset_password_content', ['appName' => config('app.name'), 'token' => $this->token]);
    }

    public function toTwilio($notifiable)
    {
        return (new TwilioSmsMessage())->content($this->smsMessage());
    }
}
