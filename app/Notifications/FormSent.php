<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FormSent extends Notification implements ShouldQueue
{
    use Queueable;

    protected $msg;

    public function __construct($request)
    {
        $this->msg = $request;

    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {

        $mailMessage = (new MailMessage)
            ->replyTo($this->msg->email, $this->msg->first_name . ' ' . $this->msg->last_name)
            ->subject(trans('mail.contact_form_title', ['country' => $this->msg->country_name, 'appName' => config('app.name')]))
            ->line(t('Country') . ': <a href="' . url('/?d=' . $this->msg->country_code) . '">' . $this->msg->country_name . '</a>')
            ->line(t('First Name') . ': ' . $this->msg->first_name)
            ->line(t('Last Name') . ': ' . $this->msg->last_name)
            ->line(t('Email Address') . ': ' . $this->msg->email)
            ->line(t('User type') . ': ' . !empty($this->msg->user_type) ? $this->msg->user_type : '');
        if (isset($this->msg->company_name) && $this->msg->company_name != '') {
            $mailMessage->line(t('Company Name') . ': ' . $this->msg->company_name);
        }

        $mailMessage->line(nl2br($this->msg->message))
            ->salutation(trans('mail.footer_salutation', ['appName' => config('app.name')]));

        return $mailMessage;
    }
}
