<?php

namespace Litepie\Form\Fields;

/**
 * Email Field
 */
class EmailField extends TextField
{
    /**
     * Get the field type.
     */
    protected function getFieldType(): string
    {
        return 'email';
    }

    /**
     * Render the field.
     */
    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="email" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? ''),
            $attributes
        );
    }
}
