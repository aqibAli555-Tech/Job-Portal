<?php

namespace App\Http\Requests;

class PostRequest extends Request
{
    public static $packages;
    public static $paymentMethods;

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    // protected function prepareForValidation()
    // {
    // 	// Don't apply this to the Admin Panel
    // 	if (isFromAdminPanel()) {
    // 		return;
    // 	}

    // 	$input = $this->all();


    // 	request()->merge($input); // Required!
    // 	$this->merge($input);
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $cat = null;

        $rules = [
            'category_id' => ['required'],
            'post_type_id' => ['required'],
            'title' => ['required',],
            'description' => ['required'],
            'salary_type_id' => ['required'],
            'contact_name' => ['required'],
            'email' => ['max:100'],
            'phone' => ['max:20'],
            'city_id' => ['required'],
        ];


        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        $attributes = [];

        if ($this->file('company.logo')) {
            $attributes['company.logo'] = t('logo');
        }

        return $attributes;
    }

    /**
     * @return array
     */
    public function messages()
    {
        $messages = [];

        // Logo
        if ($this->file('company.logo')) {
            // uploaded
            $maxSize = (int)config('settings.upload.max_image_size', 1000); // In KB
            $maxSize = $maxSize * 1024;                                     // Convert KB to Bytes
            $msg = t('large_file_uploaded_error', [
                'field' => t('logo'),
                'maxSize' => readableBytes($maxSize),
            ]);

            $uploadMaxFilesizeStr = @ini_get('upload_max_filesize');
            $postMaxSizeStr = @ini_get('post_max_size');
            if (!empty($uploadMaxFilesizeStr) && !empty($postMaxSizeStr)) {
                $uploadMaxFilesize = (int)strToDigit($uploadMaxFilesizeStr);
                $postMaxSize = (int)strToDigit($postMaxSizeStr);

                $serverMaxSize = min($uploadMaxFilesize, $postMaxSize);
                $serverMaxSize = $serverMaxSize * 1024 * 1024; // Convert MB to KB to Bytes
                if ($serverMaxSize < $maxSize) {
                    $msg = t('large_file_uploaded_error_system', [
                        'field' => t('logo'),
                        'maxSize' => readableBytes($serverMaxSize),
                    ]);
                }
            }

            $messages['company.logo.uploaded'] = $msg;
        }

        // Category & Sub-Category
        if ($this->filled('parent_id') && !empty($this->input('parent_id'))) {
            $messages['category_id.required'] = t('The field is required', ['field' => mb_strtolower(t('Sub-Category'))]);
            $messages['category_id.not_in'] = t('The field is required', ['field' => mb_strtolower(t('Sub-Category'))]);
        }

        if (config('settings.single.publication_form_type') == '2') {
            // Package & PaymentMethod
            $messages['package_id.required'] = trans('validation.required_package_id');
            $messages['payment_method_id.required'] = t('validation.required_payment_method_id');
            $messages['payment_method_id.not_in'] = t('validation.required_payment_method_id');
        }

        return $messages;
    }
}
