<?php

namespace App\Validators;

use Core\Contracts\Validator\Validator;

class RegisterValidator extends Validator
{
    public function rules(): array
    {
        return [
             'first_name'            => ['required'],
             'last_name'             => ['required'],
             'email'                 => ['required', 'email', 'unique:users,email'],
             'password'              => ['required', 'min:8', 'confirmed'],
             'password_confirmation' => ['required'],
        ];
    }
}
