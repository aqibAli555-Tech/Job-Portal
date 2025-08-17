<?php

namespace App\Http\Requests\Admin;

use App\Rules\BetweenRule;
use App\Rules\BlacklistTitleRule;
use App\Rules\BlacklistWordRule;

class CompanyRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Validation Rules
        $rules = [
            'name' => ['required', new BetweenRule(2, 200), new BlacklistTitleRule()],
            'description' => ['required', new BetweenRule(5, 1000), new BlacklistWordRule()],
        ];

        // Check 'logo' is required
        if ($this->hasFile('logo')) {
            $rules['logo'] = [
                'required',
                'image',
                'mimes:' . getUploadFileTypes('image'),
                'max:' . (int)config('settings.upload.max_image_size', 1000)
            ];
        }

        return $rules;
    }
}
