<?php

namespace Litepie\Form\Fields;

/**
 * DateTime Local Field
 * 
 * HTML5 datetime-local input type
 */
class DateTimeLocalField extends TextField
{
    /**
     * Get the field type.
     */
    protected function getFieldType(): string
    {
        return 'datetime-local';
    }

    /**
     * Render the field.
     */
    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="datetime-local" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? ''),
            $attributes
        );
    }
}
