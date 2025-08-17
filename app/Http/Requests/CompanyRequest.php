<?php

namespace App\Http\Requests;

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
            'company.name' => ['required'],
            'company.description' => ['required'],
            'company.email' => ['required'],
            'company.phone' => ['required'],
        ];

        // Check 'logo' is required
        if ($this->hasFile('company.logo')) {
            $rules['company.logo'] = [
                'required',
                'image',
                'mimes:' . getUploadFileTypes('image'),
                'max:' . (int)config('settings.upload.max_image_size', 1000)
            ];
        }

        $rules = [];

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        $messages = [];

        return $messages;
    }
}
