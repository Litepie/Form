<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Month picker field
 */
class MonthField extends Field
{
    protected function getFieldType(): string
    {
        return 'month';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="month" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? ''),
            $attributes
        );
    }
}
