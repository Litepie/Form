<?php

namespace Litepie\Form\Fields;

/**
 * Date Field
 */
class DateField extends TextField
{
    protected function getFieldType(): string
    {
        return 'date';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="date" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? ''),
            $attributes
        );
    }
}
