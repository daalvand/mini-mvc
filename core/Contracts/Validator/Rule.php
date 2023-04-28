<?php

namespace Core\Contracts\Validator;

abstract class Rule
{
    final public function __construct(
        protected Validator $validator,
        protected string    $attribute,
        protected mixed     $value,
        protected array     $params
    ) {
    }

    public function validate(): void
    {
        if (!$this->check()) {
            $this->validator->addError($this->attribute, $this->message());
        }
    }

    abstract public function check(): bool;

    abstract public function message(): string;
}
