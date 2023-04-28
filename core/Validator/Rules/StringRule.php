<?php

namespace Core\Validator\Rules;

use Core\Contracts\Validator\Rule;

class StringRule extends Rule
{

    public function check(): bool
    {
        return is_string($this->value);
    }

    public function message(): string
    {
        return 'This field must be string';
    }
}
