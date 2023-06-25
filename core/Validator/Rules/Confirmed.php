<?php

namespace Core\Validator\Rules;

use Core\Contracts\Validator\Rule;

class Confirmed extends Rule
{
    public function message(): string
    {
        return "The $this->attribute confirmation does not match.";
    }

    public function check(): bool
    {
        $confirmedAttribute = $this->params[0] ?? $this->attribute . '_confirmation';
        $confirmedValue     = $this->validator->getValueOf($confirmedAttribute);
        return $this->value === $confirmedValue;
    }
}
