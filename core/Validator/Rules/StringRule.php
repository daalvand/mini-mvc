<?php

namespace Core\Validator\Rules;

use Core\Contracts\Validator\Rule;

class StringRule extends Rule
{

    protected function check(): bool
    {
        return is_string($this->value);
    }

    protected function message(): string
    {
        return 'This field must be string';
    }
}
