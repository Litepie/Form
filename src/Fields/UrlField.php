<?php

namespace Litepie\Form\Fields;

/**
 * URL Field
 */
class UrlField extends TextField
{
    protected function getFieldType(): string
    {
        return 'url';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="url" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? ''),
            $attributes
        );
    }
}
