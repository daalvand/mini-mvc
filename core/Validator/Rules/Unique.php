<?php

namespace Core\Validator\Rules;

use Core\Contracts\Validator\Rule;

class Unique extends Rule
{
    protected function check(): bool
    {
        $table      = $this->params[0];
        $uniqueAttr = $this->params[1];
        $db         = database();
        $statement  = $db->prepare("SELECT * FROM $table WHERE $uniqueAttr = :$uniqueAttr");
        $statement->bindValue(":$uniqueAttr", $this->value);
        $statement->execute();
        $record = $statement->fetchObject();
        return !$record;
    }

    protected function message(): string
    {
        return "Record with with this value: {$this->value} already exists";
    }
}
