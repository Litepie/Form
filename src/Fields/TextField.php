<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Text Field
 */
class TextField extends Field
{
    /**
     * Get the field type.
     */
    protected function getFieldType(): string
    {
        return 'text';
    }

    /**
     * Render the field.
     */
    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="text" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? ''),
            $attributes
        );
    }
}
