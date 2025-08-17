<?php

namespace App\Http\Requests;

use App\Rules\BlacklisDomainRule;
use App\Rules\EmailRule;
use App\Rules\UsernameIsAllowedRule;
use App\Rules\UsernameIsValidRule;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class UserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        if (in_array($this->method(), ['POST', 'CREATE'])) {
            return true;
        } else {
            return auth()->check();
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param Router $router
     * @param Filesystem $files
     * @param Repository $config
     * @return array
     */
    public function rules(Router $router, Filesystem $files, Repository $config)
    {
        $rules = [];

        // CREATE
        if (in_array($this->method(), ['POST', 'CREATE'])) {
            $rules = $this->storeRules($router, $files, $config);
        }

        // UPDATE
        if (in_array($this->method(), ['PUT', 'PATCH', 'UPDATE'])) {
            $rules = $this->updateRules($router, $files, $config);
        }

        return $rules;
    }

    /**
     * @param $router
     * @param $files
     * @param $config
     * @return array
     */
    private function storeRules($router, $files, $config)
    {


        $rules = [
            //'gender_id'  => ['required', 'not_in:0'],
            'name' => ['required'],
            'user_type_id' => ['required', 'not_in:0'],
            'country_code' => ['sometimes', 'required', 'not_in:0'],
            'phone' => ['max:20', 'min:6'],
            'email' => ['max:100'],
            'password' => [
                'required',
                'min:' . config('larapen.core.passwordLength.min', 6),
                'max:' . config('larapen.core.passwordLength.max', 60),
                'dumbpwd',
                'confirmed',
            ],
            'accept_terms' => ['accepted'],
        ];

        // Email
        if ($this->filled('email')) {
            $rules['email'][] = 'email';
            $rules['email'][] = new EmailRule();
            $rules['email'][] = 'unique:users,email';
        }

        if (isEnabledField('email')) {
            if (isEnabledField('phone') && isEnabledField('email')) {
                $rules['email'][] = 'required_without:phone';
            } else {
                $rules['email'][] = 'required';
            }
        }

        // Phone
        if (config('settings.sms.phone_verification') == 1) {
            if ($this->filled('phone')) {
                $countryCode = $this->input('country_code', config('country.code'));
                if ($countryCode == 'UK') {
                    $countryCode = 'GB';
                }
                $rules['phone'][] = 'phone:' . $countryCode;
            }
        }
        if (isEnabledField('phone')) {
            if (isEnabledField('phone') && isEnabledField('email')) {
                $rules['phone'][] = 'required_without:email';
            } else {
                $rules['phone'][] = 'required';
            }
        }
        if ($this->filled('phone')) {
            $rules['phone'][] = 'unique:users,phone';
        }

        // Username
        if (isEnabledField('username')) {
            if ($this->filled('username')) {
                $rules['username'] = [
                    'between:3,100',
                    'unique:users,username',
                    new UsernameIsValidRule(),
                    new UsernameIsAllowedRule($router, $files, $config),
                ];
            }
        }

        // COMPANY: Check 'resume' is required
        if (config('larapen.core.register.showCompanyFields')) {
            if ($this->input('user_type_id') == 1) {
                $rules['company.name'] = ['required'];
                $rules['company.description'] = ['required'];

                // Check 'logo' is required
                if ($this->file('logo')) {
                    $rules['logo'] = [
                        'required',
                        'image',
                        'mimes:' . getUploadFileTypes('image'),
                        'max:' . (int)config('settings.upload.max_image_size', 1000),
                    ];
                }
            }
        }

        // CANDIDATE: Check 'resume' is required
        if (config('larapen.core.register.showResumeFields')) {
            if ($this->input('user_type_id') == 2) {
                $rules['resume.filename'] = [
                    'required',
                    'mimes:' . getUploadFileTypes('file'),
                    'max:' . (int)config('settings.upload.max_file_size', 1000),
                ];
            }
        }

        // reCAPTCHA
        // $rules = $this->recaptchaRules($rules);

        return $rules;
    }

    /**
     * @param $router
     * @param $files
     * @param $config
     * @return array
     */
    private function updateRules($router, $files, $config)
    {

        if (Str::contains(Route::currentRouteAction(), 'Account\EditController@updateSettings')) {
            $rules = [
                'password' => [
                    'min:' . config('larapen.core.passwordLength.min', 6),
                    'max:' . config('larapen.core.passwordLength.max', 60),
                    'dumbpwd',
                    'confirmed',
                ],
            ];

            if ($this->filled('user_accept_terms') && $this->input('user_accept_terms') != 1) {
                $rules['accept_terms'] = ['accepted'];
            }
        } else {
            // Check if these fields has changed
            $emailChanged = ($this->input('email') != auth()->user()->email);
            $phoneChanged = ($this->input('phone') != auth()->user()->phone);
            $usernameChanged = ($this->filled('username') && $this->input('username') != auth()->user()->username);

            // Validation Rules
            $rules = [];
            if (empty(auth()->user()->user_type_id) || auth()->user()->user_type_id == 0) {
                $rules['user_type_id'] = ['required', 'not_in:0'];
            } else {
                // $rules['gender_id'] = ['required', 'not_in:0'];
                $rules['name'] = ['required', 'max:100'];
                $rules['phone'] = ['max:20'];
                $rules['email'] = ['max:100'];
                $rules['username'] = ['between:3,100', new UsernameIsValidRule(), new UsernameIsAllowedRule($router, $files, $config)];

                // Email
                if ($this->filled('email')) {
                    $rules['email'][] = 'email';
                    $rules['email'][] = new EmailRule();
                }
                if (isEnabledField('email')) {
                    if (isEnabledField('phone') && isEnabledField('email')) {
                        $rules['email'][] = 'required_without:phone';
                    } else {
                        $rules['email'][] = 'required';
                    }
                }
                if ($emailChanged) {
                    $rules['email'][] = 'unique:users,email';
                }

                // Phone
                if (config('settings.sms.phone_verification') == 1) {
                    if ($this->filled('phone')) {
                        $countryCode = $this->input('country_code', config('country.code'));
                        if ($countryCode == 'UK') {
                            $countryCode = 'GB';
                        }
                        $rules['phone'][] = 'phone:' . $countryCode;
                    }
                }
                if (isEnabledField('phone')) {
                    if (isEnabledField('phone') && isEnabledField('email')) {
                        $rules['phone'][] = 'required_without:email';
                    } else {
                        $rules['phone'][] = 'required';
                    }
                }
                if ($phoneChanged) {
                    $rules['phone'][] = 'unique:users,phone';
                }

                // Username
                if ($usernameChanged) {
                    $rules['username'][] = 'required';
                    $rules['username'][] = 'unique:users,username';
                }
            }
        }

        return $rules;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Don't apply this to the Admin Panel
        if (isFromAdminPanel()) {
            return;
        }

        $input = $this->all();

        // name
        if ($this->filled('name')) {
            $input['name'] = strCleanerLite($this->input('name'));
            $input['name'] = onlyNumCleaner($input['name']);
        }

        // phone
        if ($this->filled('phone')) {
            $input['phone'] = phoneFormatInt($this->input('phone'), $this->input('country_code', session('country_code')));
        }


        request()->merge($input); // Required!

        $this->merge($input);
    }
}
