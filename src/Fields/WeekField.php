<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Week picker field
 */
class WeekField extends Field
{
    protected function getFieldType(): string
    {
        return 'week';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="week" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? ''),
            $attributes
        );
    }
}
