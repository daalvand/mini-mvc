<?php

namespace Core\Validator\Rules;

use Core\Contracts\Validator\Rule;

class Required extends Rule
{
    public function check(): bool
    {
        return !empty($this->value);
    }

    public function message(): string
    {
        return 'This field is required';
    }
}
