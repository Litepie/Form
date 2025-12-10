<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Gallery/Multiple Images field
 */
class GalleryField extends Field
{
    protected function getFieldType(): string
    {
        return 'file';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="file" name="%s[]" id="%s" accept="image/*" multiple %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            $attributes
        );
    }
}
