<?php

namespace App\Models\Thread;

use Illuminate\Database\Eloquent\Relations\HasMany;

trait MessageTrait
{
    /**
     * Recipients of this message.
     *
     * @return HasMany
     */
    public function recipients()
    {
        return $this->participants()->where('user_id', '!=', $this->user_id);
    }
}
