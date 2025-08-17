<?php

namespace App\Listeners;

use App\Events\UserWasLogged;
use App\Helpers\Date;
use Illuminate\Support\Carbon;

class UpdateUserLastLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param UserWasLogged $event
     * @return void
     */
    public function handle(UserWasLogged $event)
    {

        $event->user->last_login_at = Carbon::now(Date::getAppTimeZone());
        $event->user->save(['canBeSaved' => true]);
    }
}
