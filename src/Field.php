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
     * Whether field is visible.
     */
    protected bool $visible = true;

    /**
     * Whether field is readonly.
     */
    protected bool $readonly = false;

    /**
     * Whether field is disabled.
     */
    protected bool $disabled = false;

    /**
     * Visibility condition callback.
     */
    protected ?\Closure $visibilityCondition = null;

    /**
     * Permission/ability required to view this field.
     */
    protected ?string $permission = null;

    /**
     * Roles allowed to view this field.
     */
    protected array $roles = [];

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
     * Get or set field label.
     */
    public function label(?string $label = null): string|self
    {
        if ($label === null) {
            return $this->label ?: Str::title(str_replace('_', ' ', $this->name));
        }
        
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
     * Get or set field placeholder.
     */
    public function placeholder(?string $placeholder = null): self|string|null
    {
        if ($placeholder === null) {
            return $this->placeholder;
        }
        
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * Get field placeholder.
     */
    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
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
     * Get or set attributes.
     */
    public function attributes(?array $attributes = null): array|self
    {
        if ($attributes === null) {
            return $this->attributes;
        }
        
        $this->attributes = array_merge($this->attributes, $attributes);
        return $this;
    }

    /**
     * Get field attributes.
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Get or set options.
     */
    public function options(?array $options = null): array|self
    {
        if ($options === null) {
            return $this->options;
        }
        
        $this->options = $options;
        return $this;
    }

    /**
     * Get field options.
     */
    public function getOptions(): array
    {
        return $this->options;
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
     * Get or set help text.
     */
    public function help(?string $help = null): self|string|null
    {
        if ($help === null) {
            return $this->help;
        }
        
        $this->help = $help;
        return $this;
    }

    /**
     * Get field help text.
     */
    public function getHelp(): ?string
    {
        return $this->help;
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
     * Hide the field.
     */
    public function hide(): self
    {
        $this->visible = false;
        return $this;
    }

    /**
     * Show the field.
     */
    public function show(): self
    {
        $this->visible = true;
        return $this;
    }

    /**
     * Set field visibility.
     */
    public function visible(bool $visible = true): self
    {
        $this->visible = $visible;
        return $this;
    }

    /**
     * Set visibility condition (callback).
     */
    public function visibleWhen(\Closure $callback): self
    {
        $this->visibilityCondition = $callback;
        return $this;
    }

    /**
     * Set visibility based on user permission.
     */
    public function can(string $permission): self
    {
        $this->permission = $permission;
        return $this;
    }

    /**
     * Set visibility based on user roles.
     */
    public function roles(array|string $roles): self
    {
        $this->roles = is_array($roles) ? $roles : [$roles];
        return $this;
    }

    /**
     * Make field readonly.
     */
    public function readonly(bool $readonly = true): self
    {
        $this->readonly = $readonly;
        return $this;
    }

    /**
     * Make field disabled.
     */
    public function disabled(bool $disabled = true): self
    {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * Check if field is visible.
     */
    public function isVisible(?object $user = null): bool
    {
        // Check basic visibility flag
        if (!$this->visible) {
            return false;
        }

        // Check permission
        if ($this->permission && $user) {
            if (method_exists($user, 'can') && !$user->can($this->permission)) {
                return false;
            }
        }

        // Check roles
        if (!empty($this->roles) && $user) {
            if (method_exists($user, 'hasAnyRole') && !$user->hasAnyRole($this->roles)) {
                return false;
            } elseif (method_exists($user, 'hasRole')) {
                $hasRole = false;
                foreach ($this->roles as $role) {
                    if ($user->hasRole($role)) {
                        $hasRole = true;
                        break;
                    }
                }
                if (!$hasRole) {
                    return false;
                }
            }
        }

        // Check visibility condition
        if ($this->visibilityCondition) {
            return call_user_func($this->visibilityCondition, $user);
        }

        return true;
    }

    /**
     * Check if field is readonly.
     */
    public function isReadonly(): bool
    {
        return $this->readonly;
    }

    /**
     * Check if field is disabled.
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * Get field name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get or set field name.
     */
    public function name(?string $name = null): string|self
    {
        if ($name === null) {
            return $this->name;
        }
        
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
     * Get or set field type.
     */
    public function type(?string $type = null): string|self
    {
        if ($type === null) {
            return $this->type;
        }
        
        $this->type = $type;
        return $this;
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
     * Get field disabled status.
     */
    public function getDisabled(): bool
    {
        return $this->attributes['disabled'] ?? false;
    }

    /**
     * Get field readonly status.
     */
    public function getReadonly(): bool
    {
        return $this->attributes['readonly'] ?? false;
    }

    /**
     * Get field class.
     */
    public function getClass(): string
    {
        return $this->attributes['class'] ?? '';
    }

    /**
     * Get field step.
     */
    public function getStep(): ?int
    {
        return $this->attributes['step'] ?? null;
    }

    /**
     * Get field showIf condition.
     */
    public function getShowIf(): ?array
    {
        return $this->attributes['show_if'] ?? null;
    }

    /**
     * Get field hideIf condition.
     */
    public function getHideIf(): ?array
    {
        return $this->attributes['hide_if'] ?? null;
    }

    /**
     * Get accept attribute (for file fields).
     */
    public function getAccept(): ?string
    {
        return $this->attributes['accept'] ?? null;
    }

    /**
     * Get maxSize attribute (for file fields).
     */
    public function getMaxSize(): ?int
    {
        return $this->attributes['max_size'] ?? null;
    }

    /**
     * Get multiple attribute.
     */
    public function getMultiple(): bool
    {
        return $this->attributes['multiple'] ?? false;
    }

    /**
     * Get uploadUrl attribute (for file fields).
     */
    public function getUploadUrl(): ?string
    {
        return $this->attributes['upload_url'] ?? null;
    }

    /**
     * Get searchable attribute (for select fields).
     */
    public function getSearchable(): bool
    {
        return $this->attributes['searchable'] ?? false;
    }

    /**
     * Get min attribute.
     */
    public function getMin(): mixed
    {
        return $this->attributes['min'] ?? null;
    }

    /**
     * Get max attribute.
     */
    public function getMax(): mixed
    {
        return $this->attributes['max'] ?? null;
    }

    /**
     * Get format attribute (for date/time fields).
     */
    public function getFormat(): ?string
    {
        return $this->attributes['format'] ?? null;
    }

    /**
     * Get config attribute (for richtext fields).
     */
    public function getConfig(): ?array
    {
        return $this->attributes['config'] ?? null;
    }

    /**
     * Get height attribute (for richtext fields).
     */
    public function getHeight(): ?string
    {
        return $this->attributes['height'] ?? null;
    }

    /**
     * Get zoom attribute (for map fields).
     */
    public function getZoom(): ?int
    {
        return $this->attributes['zoom'] ?? null;
    }

    /**
     * Get center attribute (for map fields).
     */
    public function getCenter(): ?array
    {
        return $this->attributes['center'] ?? null;
    }

    /**
     * Get markers attribute (for map fields).
     */
    public function getMarkers(): ?array
    {
        return $this->attributes['markers'] ?? null;
    }

    /**
     * Get stepTitle attribute.
     */
    public function getStepTitle(): ?string
    {
        return $this->attributes['step_title'] ?? null;
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
        
        if ($this->readonly) {
            $attributes['readonly'] = true;
        }
        
        if ($this->disabled) {
            $attributes['disabled'] = true;
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
