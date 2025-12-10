<?php

namespace Litepie\Form\Fields;

/**
 * Number Field
 */
class NumberField extends TextField
{
    protected function getFieldType(): string
    {
        return 'number';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="number" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? ''),
            $attributes
        );
    }
}
