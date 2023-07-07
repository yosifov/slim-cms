<?php

namespace App\Models;

use App\Contracts\IValidator;

class Validator implements IValidator
{
    /**
     * The validation rules
     *
     * @var array
     */
    private array $rules;

    /**
     * The form fields data
     *
     * @var array
     */
    private array $body;

    public function __construct(array $rules, array $body)
    {
        $this->rules = $rules;
        $this->body = $body;
    }

    /**
     * Validate body using defined rules and return error messages if available
     *
     * @return array The error messages
     */
    public function validate(): array
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

    /**
     * Validate required fields
     *
     * @param string $field
     * @return string|null
     */
    private function required(string $field): ?string
    {
        $value = data_get($this->body, $field);

        if (empty($value)) {
            return "The {$field} field is required";
        }

        return null;
    }

    /**
     * Validate email fields
     *
     * @param string $field
     * @return string|null
     */
    private function email(string $field): ?string
    {
        $value = data_get($this->body, $field);

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format";
          }

        return null;
    }
}
