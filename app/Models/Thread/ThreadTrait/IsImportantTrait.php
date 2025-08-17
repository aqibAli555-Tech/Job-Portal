<?php

namespace App\Models\Thread\ThreadTrait;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait IsImportantTrait
{
    /**
     * Mark a thread as important for a user.
     *
     * @param int $userId
     *
     * @return void
     */
    public function markAsImportant($userId)
    {
        try {
            $participant = $this->getParticipantFromUser($userId);
            $participant->is_important = 1;
            $participant->save();
        } catch (ModelNotFoundException $e) { // @codeCoverageIgnore
            // do nothing
        }
    }

    /**
     * Mark a thread as not important for a user.
     *
     * @param int $userId
     *
     * @return void
     */
    public function markAsNotImportant($userId)
    {
        try {
            $participant = $this->getParticipantFromUser($userId);
            $participant->is_important = 0;
            $participant->save();
        } catch (ModelNotFoundException $e) { // @codeCoverageIgnore
            // do nothing
        }
    }

    /**
     * See if the current thread is marked as important by the user.
     *
     * @param null $userId
     * @return bool
     */
    public function isImportant($userId = null)
    {
        if (is_null($userId)) {
            try {
                if (collect($this)->has('is_important')) {
                    if ($this->is_important == 1) {
                        return true;
                    }
                }
            } catch (Exception $e) {
            }
        } else {
            try {
                $participant = $this->getParticipantFromUser($userId);

                if ($participant->is_important == 1) {
                    return true;
                }
            } catch (ModelNotFoundException $e) { // @codeCoverageIgnore
                // do nothing
            }
        }

        return false;
    }
}
