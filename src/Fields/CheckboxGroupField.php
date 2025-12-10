<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Checkbox Group Field
 * 
 * Multiple checkboxes as a group
 */
class CheckboxGroupField extends Field
{
    protected bool $inline = false;
    
    /**
     * Get the field type.
     */
    protected function getFieldType(): string
    {
        return 'checkbox_group';
    }

    /**
     * Set inline layout.
     */
    public function inline(bool $inline = true): self
    {
        $this->inline = $inline;
        return $this;
    }

    /**
     * Get inline setting.
     */
    public function isInline(): bool
    {
        return $this->inline;
    }

    /**
     * Render the field.
     */
    public function render(): string
    {
        $html = '';
        $values = (array)($this->value ?? []);
        
        foreach ($this->options as $optionValue => $optionText) {
            $checked = in_array($optionValue, $values) ? ' checked' : '';
            $id = $this->getId() . '_' . $optionValue;
            $class = $this->inline ? 'form-check-inline' : 'form-check';
            
            $html .= sprintf(
                '<div class="%s"><input type="checkbox" name="%s[]" id="%s" value="%s"%s class="form-check-input"><label for="%s" class="form-check-label">%s</label></div>',
                $class,
                htmlspecialchars($this->name),
                $id,
                htmlspecialchars($optionValue),
                $checked,
                $id,
                htmlspecialchars($optionText)
            );
        }
        
        return $html;
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'inline' => $this->inline,
        ]);
    }
}
