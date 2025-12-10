<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Rating/Stars field
 */
class RatingField extends Field
{
    protected function getFieldType(): string
    {
        return 'number';
    }

    public function render(): string
    {
        $max = $this->attributes['max'] ?? 5;
        $step = $this->attributes['allowHalf'] ?? false ? '0.5' : '1';
        
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="number" name="%s" id="%s" value="%s" min="0" max="%s" step="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? '0'),
            htmlspecialchars($max),
            $step,
            $attributes
        );
    }
}
