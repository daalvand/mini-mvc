<?php

namespace Core\Contracts\Validator;

abstract class Rule
{
    public function __construct(
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

    abstract protected function check(): bool;

    abstract protected function message(): string;
}
