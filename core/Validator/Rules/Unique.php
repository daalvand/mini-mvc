<?php

namespace Core\Validator\Rules;

use Core\Contracts\Validator\Rule;

class Unique extends Rule
{
    protected function check(): bool
    {
        $table      = $this->params[0];
        $uniqueAttr = $this->params[1];
        $record     = query_builder()->table($table)->where($uniqueAttr, $this->value)->first();
        return !$record;
    }

    protected function message(): string
    {
        return "Record with with this value: {$this->value} already exists";
    }
}
