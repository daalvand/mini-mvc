<?php

namespace Core\Validator\Rules;

use Core\Contracts\Validator\Rule;

class Email extends Rule
{
    protected function check(): bool
    {
        return filter_var($this->value, FILTER_VALIDATE_EMAIL);
    }

    protected function message(): string
    {
        return 'This field must be valid email address';
    }
}
