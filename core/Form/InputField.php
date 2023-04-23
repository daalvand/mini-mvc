<?php

namespace Core\Form;

class InputField extends BaseField
{
    public const TYPE_TEXT     = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_FILE     = 'file';
    public const TYPE_EMAIL    = 'email';
    public const TYPE_HIDDEN   = 'hidden';

    protected string $type;

    public function __construct(string $attribute, string $label = '', mixed $value = null, ?array $errors = null)
    {
        $this->type = self::TYPE_TEXT;
        parent::__construct($attribute, $label, $value, $errors);
    }

    public function renderInput(): string
    {
        $classes = $this->errors ? 'is-invalid' : '';
        return "<input type=\"$this->type\" class=\"form-control $classes\" name=\"$this->attribute\" value=\"$this->value\">";
    }

    public function passwordField(): static
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    public function fileField(): static
    {
        $this->type = self::TYPE_FILE;
        return $this;
    }

    public function emailField(): static
    {
        $this->type = self::TYPE_EMAIL;
        return $this;
    }

    public function hiddenField(): static
    {
        $this->type = self::TYPE_HIDDEN;
        return $this;
    }
}
