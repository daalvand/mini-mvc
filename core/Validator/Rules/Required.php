<?php

namespace Core\Validator\Rules;

use Core\Contracts\Validator\Rule;

class Required extends Rule
{
    protected function check(): bool
    {
        return $this->value;
    }

    protected function message(): string
    {
        return 'This field is required';
    }
}
