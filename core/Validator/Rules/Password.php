<?php

namespace Core\Validator\Rules;

use Core\Contracts\Validator\Rule;

class Password extends Rule
{
    protected string $message;

    public function message(): string
    {
        return 'The password must contain at least one uppercase letter, one lowercase letter and one number and must be at least 8 characters long.';
    }

    public function check(): bool
    {
        $length =  $this->params[0] ?? 8;
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{'.$length.',}$/', $this->value);
    }
}
