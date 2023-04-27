<?php

namespace Core\Validator\Rules;

use Core\Contracts\Validator\Rule;

class Password extends Rule
{
    public function message(): string
    {
        return 'This field must be valid password';
    }

    protected function check(): bool
    {
        $length =  $this->params[0] ?? 8;
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{'.$length.',}$/', $this->value);
    }
}
