<?php

namespace App\Validators;

use Core\Contracts\Validator\Validator;

class RegisterValidator extends Validator
{
    public function rules(): array
    {
        return [
             'first_name'            => ['required', 'string'],
             'last_name'             => ['required', 'string'],
             'email'                 => ['required', 'email', 'unique:users,email'],
             'password'              => ['required', 'string', 'password', 'confirmed'],
             'password_confirmation' => ['required'],
        ];
    }
}
