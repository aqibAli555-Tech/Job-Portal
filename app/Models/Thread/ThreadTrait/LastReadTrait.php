<?php

namespace App\Models\Thread\ThreadTrait;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

trait LastReadTrait
{
    /**
     * Mark a thread as read for a user.
     *
     * @param int $userId
     *
     * @return void
     */
    public function markAsRead($userId)
    {
        try {
            $participant = $this->getParticipantFromUser($userId);
            $participant->last_read = new Carbon();
            $participant->save();
        } catch (ModelNotFoundException $e) { // @codeCoverageIgnore
            // do nothing
        }
    }

    /**
     * Mark a thread as unread for a user.
     *
     * @param int $userId
     *
     * @return void
     */
    public function markAsUnread($userId)
    {
        try {
            $participant = $this->getParticipantFromUser($userId);
            $participant->last_read = null;
            $participant->save();
        } catch (ModelNotFoundException $e) { // @codeCoverageIgnore
            // do nothing
        }
    }

    /**
     * See if the current thread is unread by the user.
     *
     * @param int $userId
     *
     * @return bool
     */
    public function isUnread($userId = null)
    {
        if (isset($this->updated_at) && $this->updated_at instanceof Carbon) {
            if (is_null($userId)) {
                try {
                    if (collect($this)->has('last_read')) {
                        if ($this->last_read === null || $this->updated_at->gt($this->last_read)) {
                            return true;
                        }
                    }
                } catch (Exception $e) {
                }
            } else {
                try {
                    $participant = $this->getParticipantFromUser($userId);

                    if ($participant->last_read === null || $this->updated_at->gt($participant->last_read)) {
                        return true;
                    }
                } catch (ModelNotFoundException $e) { // @codeCoverageIgnore
                    // do nothing
                }
            }
        }

        return false;
    }

    /**
     * Returns count of unread messages in thread for given user.
     *
     * @param int $userId
     *
     * @return int
     */
    public function userUnreadMessagesCount($userId)
    {
        return $this->userUnreadMessages($userId)->count();
    }

    /**
     * Returns array of unread messages in thread for given user.
     *
     * @param int $userId
     *
     * @return Collection
     */
    public function userUnreadMessages($userId)
    {
        $messages = $this->messages()->where('user_id', '!=', $userId)->get();

        try {
            $participant = $this->getParticipantFromUser($userId);
        } catch (ModelNotFoundException $e) {
            return collect();
        }

        if (!$participant->last_read) {
            return $messages;
        }

        return $messages->filter(function ($message) use ($participant) {
            return $message->updated_at->gt($participant->last_read);
        });
    }
}
