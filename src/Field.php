<?php

namespace Litepie\Form;

use Illuminate\Support\Str;

/**
 * Base Field Class
 * 
 * Abstract base class for all form fields
 */
abstract class Field
{
    /**
     * Field name.
     */
    protected string $name;

    /**
     * Field type.
     */
    protected string $type;

    /**
     * Field value.
     */
    protected mixed $value = null;

    /**
     * Field label.
     */
    protected ?string $label = null;

    /**
     * Field placeholder.
     */
    protected ?string $placeholder = null;

    /**
     * Field attributes.
     */
    protected array $attributes = [];

    /**
     * Field options.
     */
    protected array $options = [];

    /**
     * Whether field is required.
     */
    protected bool $required = false;

    /**
     * Field validation rules.
     */
    protected string $validation = '';

    /**
     * Field help text.
     */
    protected ?string $help = null;

    /**
     * Field errors.
     */
    protected array $errors = [];

    /**
     * Create a new field instance.
     */
    public function __construct(string $name, array $options = [])
    {
        $this->name = $name;
        $this->type = $this->getFieldType();
        
        $this->setOptions($options);
    }

    /**
     * Get the field type.
     */
    abstract protected function getFieldType(): string;

    /**
     * Render the field.
     */
    abstract public function render(): string;

    /**
     * Set field options.
     */
    protected function setOptions(array $options): void
    {
        foreach ($options as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            } elseif ($key === 'class') {
                $this->addClass($value);
            } else {
                $this->attributes[$key] = $value;
            }
        }
    }

    /**
     * Set field value.
     */
    public function value(mixed $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Get field value.
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * Set field label.
     */
    public function label(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Get field label.
     */
    public function getLabel(): ?string
    {
        return $this->label ?: Str::title(str_replace('_', ' ', $this->name));
    }

    /**
     * Set field placeholder.
     */
    public function placeholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * Add CSS class.
     */
    public function addClass(string $class): self
    {
        $classes = explode(' ', $this->attributes['class'] ?? '');
        $classes[] = $class;
        $this->attributes['class'] = implode(' ', array_unique(array_filter($classes)));
        return $this;
    }

    /**
     * Set attribute.
     */
    public function attribute(string $key, mixed $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * Mark field as required.
     */
    public function required(bool $required = true): self
    {
        $this->required = $required;
        return $this;
    }

    /**
     * Set validation rules.
     */
    public function validation(string $rules): self
    {
        $this->validation = $rules;
        return $this;
    }

    /**
     * Set help text.
     */
    public function help(string $help): self
    {
        $this->help = $help;
        return $this;
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
     * Get field name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set field name.
     */
    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get field type.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get field ID.
     */
    public function getId(): string
    {
        return $this->attributes['id'] ?? str_replace(['[', ']'], ['_', ''], $this->name);
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

    /**
     * Build attributes string.
     */
    protected function buildAttributes(): string
    {
        $attributes = $this->attributes;
        
        if ($this->required) {
            $attributes['required'] = true;
        }
        
        $parts = [];
        foreach ($attributes as $key => $value) {
            if ($value === null || $value === false) {
                continue;
            }
            if ($value === true) {
                $parts[] = $key;
            } else {
                $parts[] = $key . '="' . htmlspecialchars($value) . '"';
            }
        }
        
        return implode(' ', $parts);
    }

    /**
     * Convert field to array.
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'value' => $this->value,
            'label' => $this->getLabel(),
            'placeholder' => $this->placeholder,
            'attributes' => $this->attributes,
            'required' => $this->required,
            'help' => $this->help,
            'errors' => $this->errors,
            'id' => $this->getId(),
        ];
    }

    /**
     * Convert to string.
     */
    public function __toString(): string
    {
        return $this->render();
    }
}
