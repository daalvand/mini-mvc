<?php

namespace Core\Form;

class TextareaField extends BaseField
{
    public function renderInput(): string
    {
        $classes = $this->errors ? 'is-invalid' : '';
        return "<textarea class=\"form-control $classes\" name=\"$this->attribute\">$this->value</textarea>";
    }
}