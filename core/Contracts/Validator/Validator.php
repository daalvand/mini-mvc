<?php

namespace Core\Contracts\Validator;

abstract class Validator
{
    protected array $errors = [];
    protected array $data = [];
    protected array $validated = [];

    abstract public function rules(): array;

    public function loadData($data): void
    {
        $this->data = $data;
    }

    public function validate(): bool|array
    {
        foreach ($this->rules() as $attribute => $rules) {
            $this->checkRules($attribute, $rules);
            if (!isset($this->errors[$attribute])) {
                $this->validated[$attribute] = $this->getValueOf($attribute);
            }
        }

        return empty($this->errors) ? $this->validated : false;
    }

    public function addError(string $attribute, string $message): void
    {
        $this->errors[$attribute][] = $message;
    }

    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    public function firstErrorOf($attribute)
    {
        $errors = $this->errors[$attribute] ?? [];
        return $errors[0] ?? '';
    }

    public function getValueOf(string $attribute): mixed
    {
        return $this->data[$attribute] ?? null;
    }

    public function data(): array
    {
        return $this->data;
    }

    public function validated(): array
    {
        return $this->validated;
    }

    public function errorsOf(string $attribute): array|null
    {
        return $this->errors[$attribute] ?? null;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    protected function checkRules(string $attribute, array $rules): void
    {
        $value = $this->data[$attribute] ?? null;
        foreach ($rules as $rule) {
            $this->resolveRuleClass($attribute, $rule, $value)->validate();
        }
    }

    protected function resolveRuleClass(string $attribute, string $rule, mixed $value): Rule
    {
        $exploded = explode(':', $rule);
        $baseName = array_shift($exploded);
        $baseName = ucfirst(strtolower($baseName));
        $clasName = __NAMESPACE__ . "\\Rules\\$baseName";
        $params   = [];
        if($exploded){
            $params = explode(',', end($exploded));
        }
        return new $clasName($this, $attribute, $value, $params);
    }
}
