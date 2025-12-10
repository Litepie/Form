<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Checkbox group field
 */
class CheckboxField extends Field
{
    protected function getFieldType(): string
    {
        return 'checkbox';
    }

    public function render(): string
    {
        if (empty($this->options)) {
            // Single checkbox
            $checked = $this->value ? ' checked' : '';
            return sprintf(
                '<input type="checkbox" name="%s" id="%s" value="1"%s class="form-check-input">',
                htmlspecialchars($this->name),
                $this->getId(),
                $checked
            );
        }
        
        // Checkbox group
        $html = '';
        $values = is_array($this->value) ? $this->value : [$this->value];
        
        foreach ($this->options as $optionValue => $optionText) {
            $checked = in_array($optionValue, $values) ? ' checked' : '';
            $id = $this->getId() . '_' . $optionValue;
            
            $html .= sprintf(
                '<div class="form-check"><input type="checkbox" name="%s[]" id="%s" value="%s"%s class="form-check-input"><label for="%s" class="form-check-label">%s</label></div>',
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
}
