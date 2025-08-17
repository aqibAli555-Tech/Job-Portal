<?php

namespace App\Http\Requests\Api\V1;

use App\Rules\EmailRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmployeeRegisterRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
            'name' => ['required'],
            'user_type_id' => ['required', 'not_in:0'],
            'country_code' => ['sometimes', 'required', 'not_in:0'],
            'email' => ['max:100'],
            'password' => [
                'required',
                'min:' . config('larapen.core.passwordLength.min', 6),
                'max:' . config('larapen.core.passwordLength.max', 60),
                'dumbpwd',

            ],
            'accept_terms' => ['required'],
            'skill_set' => ['required'],
            'employee_cv' => ['required'],
            'nationality' => ['required'],
            'experiences' => ['required'],
            'file' => ['required'],
            'city' => ['required'],
            'availability' => ['required'],
        ];
        if ($this->filled('email')) {
            $rules['email'][] = 'email';
            $rules['email'][] = new EmailRule();
            $rules['email'][] = 'unique:users,email';
        }


        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'The given data was invalid.',
            'errors' => $validator->errors()->all(),
        ], 422));
    }

}