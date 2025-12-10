<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Color field for color selection
 */
class ColorField extends Field
{
    protected function getFieldType(): string
    {
        return 'color';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="color" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? '#000000'),
            $attributes
        );
    }
}
