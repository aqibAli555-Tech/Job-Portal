<?php

namespace App\Notifications;

use App\Helpers\Date;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

class PostArchived extends Notification implements ShouldQueue
{
    use Queueable;

    protected $post;
    protected $archivedPostsExpiration;

    public function __construct(Post $post, $archivedPostsExpiration)
    {
        $this->post = $post;
        $this->archivedPostsExpiration = $archivedPostsExpiration;
    }

    public function via($notifiable)
    {
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
        $repostUrl = url('account/archived/' . $this->post->id . '/repost');

        return (new MailMessage)
            ->subject(trans('mail.post_archived_title', ['title' => $this->post->title]))
            ->greeting(trans('mail.post_archived_content_1'))
            ->line(trans('mail.post_archived_content_2', [
                'title' => $this->post->title,
                'now' => Date::format(Carbon::now(Date::getAppTimeZone())),
                'appName' => config('app.name'),
            ]))
            ->line(trans('mail.post_archived_content_3', ['repostUrl' => $repostUrl]))
            ->line(trans('mail.post_archived_content_4', [
                'dateDel' => Date::format($this->post->archived_at->addDays($this->archivedPostsExpiration)),
            ]))
            ->line(trans('mail.post_archived_content_5'))
            ->line('<br>')
            ->line(trans('mail.post_archived_content_6'))
            ->salutation(trans('mail.footer_salutation', ['appName' => config('app.name')]));
    }

    public function toNexmo($notifiable)
    {
        return (new NexmoMessage())->content($this->smsMessage())->unicode();
    }

    protected function smsMessage()
    {
        return trans('sms.post_archived_content', ['appName' => config('app.name'), 'title' => $this->post->title]);
    }

    public function toTwilio($notifiable)
    {
        return (new TwilioSmsMessage())->content($this->smsMessage());
    }
}
