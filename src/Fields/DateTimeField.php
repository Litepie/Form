<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Datetime-local field
 */
class DateTimeField extends Field
{
    protected function getFieldType(): string
    {
        return 'datetime-local';
    }

    public function render(): string
    {
        $value = $this->value;
        if ($value && !str_contains($value, 'T')) {
            $value = date('Y-m-d\TH:i', strtotime($value));
        }
        
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="datetime-local" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($value ?? ''),
            $attributes
        );
    }
}
