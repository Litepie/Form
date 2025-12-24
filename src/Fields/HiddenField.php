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

    public function toArray(): array
    {
        return parent::toArray();
    }
}
