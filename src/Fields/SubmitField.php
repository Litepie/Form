<?php

namespace Litepie\Form\Fields;

/**
 * Submit Button Field
 */
class SubmitField extends TextField
{
    protected function getFieldType(): string
    {
        return 'submit';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        $value = $this->attributes['value'] ?? 'Submit';
        
        return sprintf(
            '<input type="submit" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($value),
            $attributes
        );
    }
}
