<?php

namespace Litepie\Form\Concerns;

/**
 * Handles field attributes, classes, help text, and tooltips
 */
trait HasAttributes
{
    /**
     * Field attributes.
     */
    protected array $attributes = [];

    /**
     * Field help text.
     */
    protected ?string $help = null;

    /**
     * Tooltip text.
     */
    protected ?string $tooltip = null;

    /**
     * Example value or hint.
     */
    protected ?string $example = null;

    /**
     * Whether field is readonly.
     */
    protected bool $readonly = false;

    /**
     * Whether field is disabled.
     */
    protected bool $disabled = false;

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
     * Set format (for date/time/tel fields).
     */
    public function format(string $format): self
    {
        $this->attributes['format'] = $format;
        return $this;
    }

    /**
     * Set clearable option.
     */
    public function clearable(bool $clearable = true): self
    {
        $this->attributes['clearable'] = $clearable;
        return $this;
    }

    /**
     * Set minimum value/length.
     */
    public function min(mixed $min): self
    {
        $this->attributes['min'] = $min;
        return $this;
    }

    /**
     * Set maximum value/length.
     */
    public function max(mixed $max): self
    {
        $this->attributes['max'] = $max;
        return $this;
    }

    /**
     * Set minimum length (for text inputs).
     */
    public function minLength(int $length): self
    {
        $this->attributes['minLength'] = $length;
        return $this;
    }

    /**
     * Set maximum length (for text inputs).
     */
    public function maxLength(int $length): self
    {
        $this->attributes['maxLength'] = $length;
        return $this;
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
}
