<?php

namespace App\Listeners;

use App\Events\AppliedEmails;
use App\Helpers\Helper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddAppliedEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param AppliedEmails $event
     * @return void
     */
    public function handle(AppliedEmails $event)
    {
        $model = new \App\Models\AppliedEmails();
        $model->company_id = $event->company_id;
        $model->post_id = $event->post_id;
        $model->user_id = $event->user_id;
        $model->save();
    }
}
