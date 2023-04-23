<?php

namespace Core\Validator\Rules;

use Core\Contracts\Validator\Rule;

class Same extends Rule
{
    protected function check(): bool
    {
        $sameField = $this->params[0];
        return $this->value === $this->validator->getValueOf($sameField);
    }

    protected function message(): string
    {
        return "This field must be the same as {$this->params[0]}";
    }
}
