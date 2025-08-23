<?php

namespace Litepie\Form\Fields;

/**
 * Password Field
 */
class PasswordField extends TextField
{
    /**
     * Get the field type.
     */
    protected function getFieldType(): string
    {
        return 'password';
    }

    /**
     * Render the field.
     */
    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="password" name="%s" id="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            $attributes
        );
    }
}
