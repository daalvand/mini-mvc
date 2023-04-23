<?php

namespace Core\Validator\Rules;

use Core\Contracts\Validator\Rule;

class Max extends Rule
{
    protected function check(): bool
    {
        if (is_string($this->value)) {
            return strlen($this->value) < $this->params[0];
        }
        return $this->value < $this->params[0];
    }

    protected function message(): string
    {
        return "Max length of this field must be {$this->params[0]}";
    }
}
