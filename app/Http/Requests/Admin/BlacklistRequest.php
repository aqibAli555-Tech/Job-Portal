<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class BlacklistRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'entry' => Rule::unique('blacklist')->where(function ($query) {
                return $query->where('type', $this->type)->where('entry', $this->entry);
            })
        ];
    }
}
