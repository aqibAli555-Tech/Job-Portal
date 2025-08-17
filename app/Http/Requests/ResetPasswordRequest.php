<?php

namespace App\Http\Requests;

class ResetPasswordRequest extends AuthRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        $rules['password'] = ['required', 'min:8', 'max:60', 'dumbpwd', 'confirmed'];

        return $rules;
    }
}
