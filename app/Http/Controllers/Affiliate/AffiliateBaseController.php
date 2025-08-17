<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Thread;
use App\Http\Controllers\Traits\SettingsTrait;

class AffiliateBaseController extends Controller
{
    use SettingsTrait;

    public $threads;
    public $threadsWithNewMessage;

    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            if (auth()->check()) {
                $this->leftMenuInfo();
            }
            $this->applyFrontSettings();
            return $next($request);
        });

        view()->share('pagePath', '');
    }
    public function leftMenuInfo()
    {
        view()->share('user', auth()->user());

        $this->threads = Thread::forUser(auth()->id())->latest('updated_at');
        view()->share('countThreads', $this->threads->count());


        $this->threadsWithNewMessage = Thread::forUserWithNewMessages(auth()->id());
        view()->share('countThreadsWithNewMessage', $this->threadsWithNewMessage->count());
        $this->threadsWithNewMessage = Thread::whereHas('post', function ($query) {
            $query->currentCountry()->unarchived();
        })->forUserWithNewMessages(auth()->id());
        view()->share('countThreadsWithNewMessage', $this->threadsWithNewMessage->count());
        
        $message_notification=Notification::get_notification_by_type('message')->count();
        view()->share('messagenotificationcount', $message_notification);
    }
}

?>