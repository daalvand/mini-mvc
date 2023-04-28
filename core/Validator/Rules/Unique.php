<?php

namespace Core\Validator\Rules;

use Core\Contracts\Validator\Rule;
use Exception;

class Unique extends Rule
{
    /**
     * @throws Exception
     */
    public function check(): bool
    {
        if (count($this->params) !== 2) {
            throw new Exception('Unique rule must have 2 parameters');
        }
        if (!$this->value) {
            return true;
        }
        [$table, $uniqueAttr] = $this->params;
        return !query_builder()->table($table)->where($uniqueAttr, $this->value)->exists();
    }

    public function message(): string
    {
        return "Record with this value: $this->value already exists";
    }
}
