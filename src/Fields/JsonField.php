<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * JSON Editor Field
 * 
 * For JSON data with validation and formatting
 */
class JsonField extends Field
{
    protected bool $validate = true;
    protected bool $format = true;
    protected int $indent = 2;
    protected int $height = 300;
    
    /**
     * Get the field type.
     */
    protected function getFieldType(): string
    {
        return 'json';
    }

    /**
     * Enable/disable JSON validation.
     */
    public function validate(bool $validate = true): self
    {
        $this->validate = $validate;
        return $this;
    }

    /**
     * Get validation setting.
     */
    public function shouldValidate(): bool
    {
        return $this->validate;
    }

    /**
     * Enable/disable auto-formatting.
     */
    public function format(bool $format = true): self
    {
        $this->format = $format;
        return $this;
    }

    /**
     * Get format setting.
     */
    public function shouldFormat(): bool
    {
        return $this->format;
    }

    /**
     * Set indentation spaces.
     */
    public function indent(int $spaces): self
    {
        $this->indent = $spaces;
        return $this;
    }

    /**
     * Get indent setting.
     */
    public function getIndent(): int
    {
        return $this->indent;
    }

    /**
     * Set editor height.
     */
    public function height(int $height): self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * Get JSON editor height.
     */
    public function getJsonHeight(): int
    {
        return $this->height;
    }

    /**
     * Render the field.
     */
    public function render(): string
    {
        $attributes = $this->buildAttributes();
        $value = $this->value;
        
        // Format JSON if it's valid and format is enabled
        if ($this->format && is_string($value)) {
            $decoded = json_decode($value);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            }
        } elseif (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }
        
        return sprintf(
            '<textarea name="%s" id="%s" class="json-editor" data-validate="%s" data-format="%s" data-indent="%d" style="height: %dpx; font-family: monospace;" %s>%s</textarea>',
            htmlspecialchars($this->name),
            $this->getId(),
            $this->validate ? 'true' : 'false',
            $this->format ? 'true' : 'false',
            $this->indent,
            $this->height,
            $attributes,
            htmlspecialchars($value ?? '{}')
        );
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'validate' => $this->validate,
            'format' => $this->format,
            'indent' => $this->indent,
            'height' => $this->height,
        ]);
    }
}
