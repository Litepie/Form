<?php

namespace Litepie\Form\Fields;

/**
 * Tel Field
 */
class TelField extends TextField
{
    protected function getFieldType(): string
    {
        return 'tel';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="tel" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? ''),
            $attributes
        );
    }
}
