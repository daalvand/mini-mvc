<?php

namespace App\Validators;


use Core\Contracts\Validator\Validator;

class LoginValidator extends Validator
{
    public function rules(): array
    {
        return [
             'email'    => ['required', 'string', 'email'],
             'password' => ['required', 'string'],
        ];
    }
}
