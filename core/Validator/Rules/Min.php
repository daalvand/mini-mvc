<?php

namespace Core\Validator\Rules;

use Core\Contracts\Validator\Rule;

class Min extends Rule
{
    public function check(): bool
    {
        if (is_string($this->value)) {
            return strlen($this->value) > $this->params[0];
        }
        return $this->value > $this->params[0];
    }

    public function message(): string
    {
        return "Min length of this field must be {$this->params[0]}";
    }
}
