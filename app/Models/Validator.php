<?php

namespace App\Models;

class Validator
{
    private array $rules;

    private array $body;

    public function __construct(array $rules, array $body)
    {
        $this->rules = $rules;
        $this->body = $body;
    }

    public function validate()
    {
        $errors = [];

        foreach ($this->rules as $field => $rule) {
            $fieldRules = explode('|', $rule);
            
            if (!array_key_exists($field, $this->body)) continue;

            foreach ($fieldRules as $fieldRule) {
                if (!empty($errors[$field])) continue;

                if (method_exists($this, $fieldRule) && $this->{$fieldRule}($field)) {
                    $errors[$field] = $this->{$fieldRule}($field);
                }
            }
        }

        return $errors;
    }

    private function required(string $field): ?string
    {
        $value = data_get($this->body, $field);

        if (empty($value)) {
            return "The {$field} field is required";
        }

        return null;
    }

    private function email(string $field): ?string
    {
        $value = data_get($this->body, $field);

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format";
          }

        return null;
    }
}