<?php

namespace App\Http\Requests\Admin;

class PermissionRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        if (in_array($this->method(), ['POST', 'CREATE'])) {
            $rules['name'] = ['required', 'unique:permissions,name'];
        }

        return $rules;
    }
}
