<?php

namespace Litepie\Form\Fields;

/**
 * Hidden Field
 */
class HiddenField extends TextField
{
    protected function getFieldType(): string
    {
        return 'hidden';
    }

    public function render(): string
    {
        return sprintf(
            '<input type="hidden" name="%s" id="%s" value="%s">',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? '')
        );
    }
}
