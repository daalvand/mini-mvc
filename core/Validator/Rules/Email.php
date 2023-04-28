<?php

namespace Core\Validator\Rules;

use Core\Contracts\Validator\Rule;

class Email extends Rule
{
    public function check(): bool
    {
        return filter_var($this->value, FILTER_VALIDATE_EMAIL);
    }

    public function message(): string
    {
        return 'This field must be a valid email address';
    }
}
