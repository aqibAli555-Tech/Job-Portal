<?php

namespace App\Notifications;

use App\Helpers\Date;
use App\Helpers\UrlGen;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class PostNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $post;

    public function __construct($post)
    {
        $this->post = $post;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $postUrl = UrlGen::post($this->post);

        return (new MailMessage)
            ->subject(trans('mail.post_notification_title'))
            ->greeting(trans('mail.post_notification_content_1'))
            ->line(trans('mail.post_notification_content_2', ['advertiserName' => $this->post->contact_name]))
            ->line(trans('mail.post_notification_content_3', [
                'postUrl' => $postUrl,
                'title' => $this->post->title,
                'now' => Date::format(Carbon::now(Date::getAppTimeZone())),
                'time' => Carbon::now(Date::getAppTimeZone())->format('H:i'),
            ]))
            ->salutation(trans('mail.footer_salutation', ['appName' => config('app.name')]));
    }
}
