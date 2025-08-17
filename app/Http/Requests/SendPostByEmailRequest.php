<?php

namespace App\Http\Requests;

use App\Rules\EmailRule;

class SendPostByEmailRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'sender_email' => ['required', 'email', new EmailRule(), 'max:100'],
            'recipient_email' => ['required', 'email', new EmailRule(), 'max:100'],
            //'message' 	  => ['required', new BetweenRule(20, 500)],
            'post' => ['required', 'numeric'],
        ];

        return $rules;
    }
}
