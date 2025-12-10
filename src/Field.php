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
     * Declarative visibility conditions (field, operator, value).
     */
    protected array $visibilityConditions = [];

    /**
     * Permission/ability required to view this field.
     */
    protected ?string $permission = null;

    /**
     * Roles allowed to view this field.
     */
    protected array $roles = [];

    /**
     * Field width in grid columns.
     */
    protected ?int $width = null;

    /**
     * Total columns in grid (default: 12).
     */
    protected int $totalColumns = 12;

    /**
     * Default width if not specified.
     */
    protected static int $defaultWidth = 6;

    /**
     * Row identifier for grouping fields.
     */
    protected ?string $row = null;

    /**
     * Group identifier (top-level grouping).
     */
    protected ?string $group = null;

    /**
     * Section identifier (sub-group within a group).
     */
    protected ?string $section = null;

    /**
     * Tooltip text.
     */
    protected ?string $tooltip = null;

    /**
     * Example value or hint.
     */
    protected ?string $example = null;

    /**
     * Field this field depends on.
     */
    protected ?string $dependsOn = null;

    /**
     * Computed field callback.
     */
    protected ?\Closure $computedCallback = null;

    /**
     * Conditional required rules.
     */
    protected array $requiredConditions = [];

    /**
     * Custom validation messages.
     */
    protected array $validationMessages = [];

    /**
     * Loading text for async operations.
     */
    protected ?string $loadingText = null;

    /**
     * Confirmation message for changes.
     */
    protected ?string $confirmMessage = null;

    /**
     * Whether to track changes.
     */
    protected bool $trackChanges = false;

    /**
     * Number of columns for layout.
     */
    protected ?int $columns = null;

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
     * Set or get tooltip text.
     */
    public function tooltip(?string $tooltip = null): self|string|null
    {
        if ($tooltip === null) {
            return $this->tooltip;
        }
        
        $this->tooltip = $tooltip;
        return $this;
    }

    /**
     * Get tooltip text.
     */
    public function getTooltip(): ?string
    {
        return $this->tooltip;
    }

    /**
     * Set or get example value.
     */
    public function example(?string $example = null): self|string|null
    {
        if ($example === null) {
            return $this->example;
        }
        
        $this->example = $example;
        return $this;
    }

    /**
     * Get example value.
     */
    public function getExample(): ?string
    {
        return $this->example;
    }

    /**
     * Set field dependency.
     */
    public function dependsOn(string $fieldName): self
    {
        $this->dependsOn = $fieldName;
        return $this;
    }

    /**
     * Get field dependency.
     */
    public function getDependsOn(): ?string
    {
        return $this->dependsOn;
    }

    /**
     * Set computed field callback.
     */
    public function computed(\Closure $callback): self
    {
        $this->computedCallback = $callback;
        return $this;
    }

    /**
     * Check if field is computed.
     */
    public function isComputed(): bool
    {
        return $this->computedCallback !== null;
    }

    /**
     * Compute field value.
     */
    public function computeValue(array $data): mixed
    {
        if ($this->computedCallback) {
            return call_user_func($this->computedCallback, $data);
        }
        
        return $this->value;
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
     * Set loading text.
     */
    public function loadingText(string $text): self
    {
        $this->loadingText = $text;
        return $this;
    }

    /**
     * Get loading text.
     */
    public function getLoadingText(): ?string
    {
        return $this->loadingText;
    }

    /**
     * Set confirmation message.
     */
    public function confirmChange(string $message): self
    {
        $this->confirmMessage = $message;
        return $this;
    }

    /**
     * Get confirmation message.
     */
    public function getConfirmMessage(): ?string
    {
        return $this->confirmMessage;
    }

    /**
     * Enable change tracking.
     */
    public function trackChanges(bool $track = true): self
    {
        $this->trackChanges = $track;
        return $this;
    }

    /**
     * Check if changes are tracked.
     */
    public function isTrackingChanges(): bool
    {
        return $this->trackChanges;
    }

    /**
     * Set number of columns for layout.
     */
    public function columns(int|array $columns): self
    {
        $this->columns = is_array($columns) ? $columns : $columns;
        return $this;
    }

    /**
     * Get columns.
     */
    public function getColumns(): ?int
    {
        return $this->columns;
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
     * Set visibility condition (callback or declarative).
     * 
     * Usage:
     *   ->visibleWhen(fn($data) => $data['type'] === 'premium')
     *   ->visibleWhen('type', '=', 'premium')
     *   ->visibleWhen('age', '>', 18)
     */
    public function visibleWhen(...$args): self
    {
        if (count($args) === 1 && $args[0] instanceof \Closure) {
            // Closure-based condition
            $this->visibilityCondition = $args[0];
        } elseif (count($args) === 3) {
            // Declarative condition: field, operator, value
            [$field, $operator, $value] = $args;
            $this->visibilityConditions[] = [
                'field' => $field,
                'operator' => $operator,
                'value' => $value,
            ];
        }
        return $this;
    }

    /**
     * Get visibility conditions.
     */
    public function getVisibilityConditions(): array
    {
        return $this->visibilityConditions;
    }

    /**
     * Check if field meets visibility conditions.
     */
    public function meetsVisibilityConditions(array $data): bool
    {
        // Check closure-based condition
        if ($this->visibilityCondition !== null) {
            return call_user_func($this->visibilityCondition, $data);
        }

        // Check declarative conditions (all must be true)
        if (empty($this->visibilityConditions)) {
            return true;
        }

        foreach ($this->visibilityConditions as $condition) {
            // Support dot notation for nested data
            $fieldValue = $data[$condition['field']] ?? null;
            if (str_contains($condition['field'], '.')) {
                // Handle nested fields like 'address.city'
                $keys = explode('.', $condition['field']);
                $fieldValue = $data;
                foreach ($keys as $key) {
                    if (is_array($fieldValue) && isset($fieldValue[$key])) {
                        $fieldValue = $fieldValue[$key];
                    } else {
                        $fieldValue = null;
                        break;
                    }
                }
            }
            
            switch ($condition['operator']) {
                case '=':
                case '==':
                    if ($fieldValue != $condition['value']) return false;
                    break;
                case '===':
                    if ($fieldValue !== $condition['value']) return false;
                    break;
                case '!=':
                    if ($fieldValue == $condition['value']) return false;
                    break;
                case '!==':
                    if ($fieldValue === $condition['value']) return false;
                    break;
                case '>':
                    if ($fieldValue <= $condition['value']) return false;
                    break;
                case '>=':
                    if ($fieldValue < $condition['value']) return false;
                    break;
                case '<':
                    if ($fieldValue >= $condition['value']) return false;
                    break;
                case '<=':
                    if ($fieldValue > $condition['value']) return false;
                    break;
                case 'in':
                    if (!in_array($fieldValue, (array)$condition['value'])) return false;
                    break;
                case 'not_in':
                    if (in_array($fieldValue, (array)$condition['value'])) return false;
                    break;
                case 'contains':
                    if (strpos($fieldValue, $condition['value']) === false) return false;
                    break;
                case 'starts_with':
                    if (!str_starts_with($fieldValue, $condition['value'])) return false;
                    break;
                case 'ends_with':
                    if (!str_ends_with($fieldValue, $condition['value'])) return false;
                    break;
                default:
                    return false;
            }
        }

        return true;
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
     * Set field width in columns.
     */
    public function width(int $columns, int $total = 12): self
    {
        $this->width = $columns;
        $this->totalColumns = $total;
        return $this;
    }

    /**
     * Set field column span (shorthand for width).
     */
    public function col(int $columns): self
    {
        return $this->width($columns, 12);
    }

    /**
     * Get field width (returns default if not set).
     */
    public function getWidth(): int
    {
        return $this->width ?? self::$defaultWidth;
    }

    /**
     * Get total columns in grid.
     */
    public function getTotalColumns(): int
    {
        return $this->totalColumns;
    }

    /**
     * Set default width for all fields.
     */
    public static function setDefaultWidth(int $width): void
    {
        self::$defaultWidth = $width;
    }

    /**
     * Get default width.
     */
    public static function getDefaultWidth(): int
    {
        return self::$defaultWidth;
    }

    /**
     * Set row identifier for grouping.
     */
    public function row(string $row): self
    {
        $this->row = $row;
        return $this;
    }

    /**
     * Get row identifier.
     */
    public function getRow(): ?string
    {
        return $this->row;
    }

    /**
     * Set group identifier.
     */
    public function group(string $group): self
    {
        $this->group = $group;
        return $this;
    }

    /**
     * Get group identifier.
     */
    public function getGroup(): ?string
    {
        return $this->group;
    }

    /**
     * Set section identifier.
     */
    public function section(string $section): self
    {
        $this->section = $section;
        return $this;
    }

    /**
     * Get section identifier.
     */
    public function getSection(): ?string
    {
        return $this->section;
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
            'tooltip' => $this->tooltip,
            'example' => $this->example,
            'errors' => $this->errors,
            'id' => $this->getId(),
            'dependsOn' => $this->dependsOn,
            'isComputed' => $this->isComputed(),
            'requiredConditions' => $this->requiredConditions,
            'validationMessages' => $this->validationMessages,
            'loadingText' => $this->loadingText,
            'confirmMessage' => $this->confirmMessage,
            'trackChanges' => $this->trackChanges,
            'columns' => $this->columns,
            'visibilityConditions' => $this->visibilityConditions,
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
