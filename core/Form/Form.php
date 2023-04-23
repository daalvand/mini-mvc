<?php

namespace Core\Form;

use Exception;

class Form
{
    public static function create(): static
    {
        return new static();
    }

    public function begin($action, $method, $options = []): string
    {
        $attributes = '';
        foreach ($options as $key => $value) {
            $attributes .= " $key=\"$value\"";
        }
        return "<form action='$action' method='$method' $attributes>";
    }

    public function end(): string
    {
        return '</form>';
    }

    public function inputField(string $attribute): InputField
    {
        return new InputField($attribute);
    }

    public function fileField(string $attribute): InputField
    {
        return (new InputField($attribute))->fileField();
    }

    public function passwordField(string $attribute): InputField
    {
        return (new InputField($attribute))->passwordField();
    }

    public function emailField(string $attribute): InputField
    {
        return (new InputField($attribute))->emailField();
    }

    public function textAreaField(string $attribute): TextareaField
    {
        return new TextareaField($attribute);
    }

    /**
     * @throws Exception
     */
    public function csrfField(): InputField
    {
        $csrf = bin2hex(random_bytes(50));
        session()->setTemp('csrf_token', $csrf);
        return (new InputField('csrf_token'))->hiddenField()->setValue($csrf);
    }

}
