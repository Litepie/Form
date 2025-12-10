<?php

namespace Litepie\Form\Fields;

/**
 * Reset Button Field
 */
class ResetField extends TextField
{
    protected function getFieldType(): string
    {
        return 'reset';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        $value = $this->attributes['value'] ?? 'Reset';
        
        return sprintf(
            '<input type="reset" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($value),
            $attributes
        );
    }
}
