<?php

namespace App\Validators;

use Core\Contracts\Validator\Validator;

class RegisterValidator extends Validator
{
    public function rules(): array
    {
        return [
            'firstname'       => ['required'],
            'lastname'        => ['required'],
            'email'           => ['required', 'email', 'unique:users,email'],
            'password'        => ['required', 'min:8'],
            'passwordConfirm' => ['required', 'same:password'],
        ];
    }
}
