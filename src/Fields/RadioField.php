<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Radio button group field
 */
class RadioField extends Field
{
    protected function getFieldType(): string
    {
        return 'radio';
    }

    public function render(): string
    {
        $html = '';
        foreach ($this->options as $optionValue => $optionText) {
            $checked = $this->value == $optionValue ? ' checked' : '';
            $id = $this->getId() . '_' . $optionValue;
            
            $html .= sprintf(
                '<div class="form-check"><input type="radio" name="%s" id="%s" value="%s"%s class="form-check-input"><label for="%s" class="form-check-label">%s</label></div>',
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
