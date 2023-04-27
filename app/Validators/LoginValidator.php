<?php

namespace App\Validators;


use Core\Contracts\Validator\Validator;

class LoginValidator extends Validator
{
    public function rules(): array
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}
