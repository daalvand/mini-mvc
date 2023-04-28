<?php

namespace Core\Validator\Rules;

use Core\Contracts\Validator\Rule;

class Max extends Rule
{
    public function check(): bool
    {
        $max = $this->params[0];
        if (is_numeric($this->value)) {
            return $this->value <= $max;
        }
        if (is_string($this->value)) {
            return mb_strlen($this->value) <= $max;
        }
        if (is_array($this->value)) {
            return count($this->value) <= $this->params[0];
        }
        return false;
    }

    public function message(): string
    {
        if (is_numeric($this->value)) {
            return "Max value of this field must be {$this->params[0]}";
        }

        if (is_array($this->value)) {
            return "Max count of this field must be {$this->params[0]}";
        }

        return "Max length of this field must be {$this->params[0]}";
    }
}
