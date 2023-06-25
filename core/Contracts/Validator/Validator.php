<?php

namespace Core\Contracts\Validator;

class Validator
{
    protected array $errors    = [];
    protected array $data      = [];
    protected array $validated = [];

    public static function make(): static
    {
        return new static();
    }

    public function validate(array $data, array $rules = null): array|false
    {
        $this->data = $data;
        $rules      ??= $this->rules();
        foreach ($rules as $attribute => $subRules) {
            $this->checkRules($attribute, $subRules);
            if (!isset($this->errors[$attribute])) {
                $this->validated[$attribute] = $this->getValueOf($attribute);
            }
        }

        return empty($this->errors) ? $this->validated : false;
    }

    public function rules(): array
    {
        return [];
    }

    public function addError(string $attribute, string $message): void
    {
        $this->errors[$attribute][] = $message;
    }

    public function hasError(string $attribute): bool
    {
        return (bool)($this->errors[$attribute] ?? false);
    }

    public function firstErrorOf(string $attribute): string|false
    {
        $errors = $this->errors[$attribute] ?? [];
        return reset($errors);
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
        $baseName = strtolower($baseName);
        $clasName = app()->getConfig('validator')['rules'][$baseName];
        $params   = [];
        if ($exploded) {
            $params = explode(',', end($exploded));
        }
        return new $clasName($this, $attribute, $value, $params);
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }
}
