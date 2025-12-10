<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Select Field
 */
class SelectField extends Field
{
    protected function getFieldType(): string
    {
        return 'select';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        $optionsHtml = '';
        
        foreach ($this->options as $value => $text) {
            $selected = $this->value == $value ? ' selected' : '';
            $optionsHtml .= sprintf(
                '<option value="%s"%s>%s</option>',
                htmlspecialchars($value),
                $selected,
                htmlspecialchars($text)
            );
        }
        
        return sprintf(
            '<select name="%s" id="%s" %s>%s</select>',
            htmlspecialchars($this->name),
            $this->getId(),
            $attributes,
            $optionsHtml
        );
    }
}
