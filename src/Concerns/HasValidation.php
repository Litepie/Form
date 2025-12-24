<?php

namespace Litepie\Form\Concerns;

/**
 * Handles field validation rules, messages, and required conditions
 */
trait HasValidation
{
    /**
     * Whether field is required.
     */
    protected bool $required = false;

    /**
     * Field validation rules.
     */
    protected string $validation = '';

    /**
     * Field errors.
     */
    protected array $errors = [];

    /**
     * Conditional required rules.
     */
    protected array $requiredConditions = [];

    /**
     * Custom validation messages.
     */
    protected array $validationMessages = [];

    /**
     * Mark field as required.
     */
    public function required(bool $required = true): self
    {
        $this->required = $required;
        return $this;
    }

    /**
     * Get or set validation rules.
     */
    public function validation(?string $rules = null): string|self
    {
        if ($rules === null) {
            return $this->validation;
        }
        
        $this->validation = $rules;
        return $this;
    }

    /**
     * Get field validation rules.
     */
    public function getRules(): string
    {
        return $this->validation;
    }

    /**
     * Get field validation messages.
     */
    public function getMessages(): array
    {
        return [];
    }

    /**
     * Set conditional required rule.
     */
    public function requiredWhen(string $field, string $operator, mixed $value): self
    {
        $this->requiredConditions[] = [
            'field' => $field,
            'operator' => $operator,
            'value' => $value,
        ];
        
        return $this;
    }

    /**
     * Get required conditions.
     */
    public function getRequiredConditions(): array
    {
        return $this->requiredConditions;
    }

    /**
     * Set custom validation message.
     */
    public function validationMessage(string $rule, string $message): self
    {
        $this->validationMessages[$rule] = $message;
        return $this;
    }

    /**
     * Get validation messages.
     */
    public function getValidationMessages(): array
    {
        return $this->validationMessages;
    }

    /**
     * Set errors.
     */
    public function errors(array $errors): self
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * Check if field has errors.
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Get field errors.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Check if field is required.
     */
    public function isRequired(): bool
    {
        return $this->required;
    }
}
