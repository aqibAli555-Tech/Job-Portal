<?php

namespace App\Http\Requests\Admin;

class PageRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => ['required', 'min:2', 'max:255'],
            'title' => ['max:255'],
            'content' => ['max:16000000'],
        ];

        if ($this->filled('external_link')) {
            $rules['external_link'] = ['url'];
        } else {
            $rules['title'][] = 'required';
            $rules['title'][] = 'min:2';
            $rules['content'][] = 'required';
        }

        return $rules;
    }
}
