<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Textarea Field
 */
class TextareaField extends Field
{
    protected function getFieldType(): string
    {
        return 'textarea';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<textarea name="%s" id="%s" %s>%s</textarea>',
            htmlspecialchars($this->name),
            $this->getId(),
            $attributes,
            htmlspecialchars($this->value ?? '')
        );
    }
}
