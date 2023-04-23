<?php

namespace Core\Form;

abstract class BaseField
{
    public function __construct(
        protected string     $attribute,
        protected string     $label = '',
        protected mixed      $value = null,
        protected array|null $errors = null
    ) {
        if (is_string($this->value)) {
            $this->value = htmlentities($this->value, ENT_QUOTES, 'UTF-8');
        }
    }

    public function __toString()
    {
        $input = $this->renderInput();
        $error = $this->errors ? reset($this->errors) : null;
        return "<div class=\"form-group\">
                    <label>$this->label</label>
                    $input
                    <div class=\"invalid-feedback\">$error</div>
                </div>";
    }

    abstract public function renderInput();

    public function setLabel(string $label): static
    {
        $this->label = $label;
        return $this;
    }

    public function setErrors(?array $errors): static
    {
        $this->errors = $errors;
        return $this;
    }

    public function setValue(mixed $value): static
    {
        $this->value = $value;
        return $this;
    }
}