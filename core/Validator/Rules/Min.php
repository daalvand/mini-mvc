<?php

namespace Core\Validator\Rules;

use Core\Contracts\Validator\Rule;

class Min extends Rule
{
    public function check(): bool
    {
        $min = $this->params[0];
        if (is_numeric($this->value)) {
            return $this->value >= $min;
        }
        if (is_string($this->value)) {
            return mb_strlen($this->value) >= $min;
        }
        if (is_array($this->value)) {
            return count($this->value) >= $this->params[0];
        }
        return false;
    }

    public function message(): string
    {
        if (is_numeric($this->value)) {
            return "Min value of this field must be {$this->params[0]}";
        }

        if (is_array($this->value)) {
            return "Min count of this field must be {$this->params[0]}";
        }

        return "Min length of this field must be {$this->params[0]}";
    }
}
