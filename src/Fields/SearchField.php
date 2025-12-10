<?php

namespace Litepie\Form\Fields;

/**
 * Search Field
 */
class SearchField extends TextField
{
    protected function getFieldType(): string
    {
        return 'search';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="search" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? ''),
            $attributes
        );
    }
}
