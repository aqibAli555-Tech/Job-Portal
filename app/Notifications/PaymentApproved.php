<?php

namespace App\Notifications;

use App\Helpers\UrlGen;
use App\Models\Package;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

class PaymentApproved extends Notification implements ShouldQueue
{
    use Queueable;

    protected $payment;
    protected $post;
    protected $package;
    protected $paymentMethod;

    public function __construct(Payment $payment, Post $post)
    {
        $this->payment = $payment;
        $this->post = $post;
        $this->package = Package::find($payment->package_id);
        $this->paymentMethod = PaymentMethod::find($payment->payment_method_id);
    }

    public function via($notifiable)
    {
        if ($this->payment->active != 1) {
            return false;
        }

        if (!empty($this->post->email)) {
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
        $postUrl = UrlGen::post($this->post);

        return (new MailMessage)
            ->subject(trans('mail.payment_approved_title'))
            ->greeting(trans('mail.payment_approved_content_1'))
            ->line(trans('mail.payment_approved_content_2', [
                'postUrl' => $postUrl,
                'title' => $this->post->title,
            ]))
            ->line(trans('mail.payment_approved_content_3'))
            ->line(trans('mail.payment_approved_content_4', [
                'adId' => $this->post->id,
                'packageName' => (!empty($this->package->short_name)) ? $this->package->short_name : $this->package->name,
                'amount' => $this->package->price,
                'currency' => $this->package->currency_code,
                'paymentMethodName' => $this->paymentMethod->display_name
            ]))
            ->salutation(trans('mail.footer_salutation', ['appName' => config('app.name')]));
    }

    public function toNexmo($notifiable)
    {
        return (new NexmoMessage())->content($this->smsMessage())->unicode();
    }

    protected function smsMessage()
    {
        return trans('sms.payment_approved_content', ['appName' => config('app.name'), 'title' => $this->post->title]);
    }

    public function toTwilio($notifiable)
    {
        return (new TwilioSmsMessage())->content($this->smsMessage());
    }
}
