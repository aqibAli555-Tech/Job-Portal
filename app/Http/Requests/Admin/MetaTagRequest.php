<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class MetaTagRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'page' => ['required'],
            'title' => ['required', 'max:200'],
            'description' => ['required', 'max:255'],
            'keywords' => ['max:255'],
        ];

        if (in_array($this->method(), ['POST', 'CREATE'])) {
            // Unique with additional Where Clauses
            $uniquePage = Rule::unique('meta_tags')->where(function ($query) {
                return $query->where('page', $this->page);
            });

            $rules['page'][] = $uniquePage;
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        $messages = [];

        $messages['page.unique'] = trans('admin.A meta-tag entry already exists for this page');

        return $messages;
    }
}
