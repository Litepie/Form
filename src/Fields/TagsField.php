<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Tags input field
 */
class TagsField extends Field
{
    protected function getFieldType(): string
    {
        return 'text';
    }

    public function render(): string
    {
        $tags = is_array($this->value) ? implode(',', $this->value) : $this->value;
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="text" name="%s" id="%s" value="%s" %s data-role="tagsinput">',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($tags ?? ''),
            $attributes
        );
    }
}
