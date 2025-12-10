<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Range/Slider field for numeric ranges
 */
class RangeField extends Field
{
    protected function getFieldType(): string
    {
        return 'range';
    }

    public function render(): string
    {
        $min = $this->attributes['min'] ?? 0;
        $max = $this->attributes['max'] ?? 100;
        $step = $this->attributes['step'] ?? 1;
        
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="range" name="%s" id="%s" value="%s" min="%s" max="%s" step="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? $min),
            htmlspecialchars($min),
            htmlspecialchars($max),
            htmlspecialchars($step),
            $attributes
        );
    }
}
