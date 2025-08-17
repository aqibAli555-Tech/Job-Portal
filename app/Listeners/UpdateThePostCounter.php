<?php

namespace App\Listeners;

use App\Events\PostWasVisited;

class UpdateThePostCounter
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param PostWasVisited $event
     * @return bool|void
     */
    public function handle(PostWasVisited $event)
    {
        // Don't count the self-visits
        if (auth()->check()) {
            if (auth()->user()->id == $event->post->user_id) {
                return false;
            }
        }

        if (!session()->has('postIsVisited')) {
            return $this->updateCounter($event->post);
        } else {
            if (session()->get('postIsVisited') != $event->post->id) {
                return $this->updateCounter($event->post);
            } else {
                return false;
            }
        }
    }

    /**
     * @param $post
     */
    public function updateCounter($post)
    {
        $post->visits = $post->visits + 1;
        $post->save(['canBeSaved' => true]);
        session()->put('postIsVisited', $post->id);
    }
}
