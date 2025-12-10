<?php

namespace Litepie\Form\Fields;

/**
 * Button Field
 */
class ButtonField extends TextField
{
    protected function getFieldType(): string
    {
        return 'button';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        $text = $this->attributes['text'] ?? $this->attributes['value'] ?? 'Button';
        
        return sprintf(
            '<button type="button" name="%s" id="%s" %s>%s</button>',
            htmlspecialchars($this->name),
            $this->getId(),
            $attributes,
            htmlspecialchars($text)
        );
    }
}
