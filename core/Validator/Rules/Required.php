<?php

namespace Core\Validator\Rules;

use Core\Contracts\Validator\Rule;

class Required extends Rule
{
    protected function check(): bool
    {
        return !empty($this->value);
    }

    protected function message(): string
    {
        return 'This field is required';
    }
}
