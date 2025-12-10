<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Time picker field
 */
class TimeField extends Field
{
    protected function getFieldType(): string
    {
        return 'time';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="time" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? ''),
            $attributes
        );
    }
}
