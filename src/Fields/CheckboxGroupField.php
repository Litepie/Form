<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Checkbox Group Field
 * 
 * Multiple checkboxes as a group
 */
class CheckboxGroupField extends Field
{
    protected bool $inline = false;
    
    /**
     * Get the field type.
     */
    protected function getFieldType(): string
    {
        return 'checkbox_group';
    }

    /**
     * Set inline layout.
     */
    public function inline(bool $inline = true): self
    {
        $this->inline = $inline;
        return $this;
    }

    /**
     * Get inline setting.
     */
    public function isInline(): bool
    {
        return $this->inline;
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'inline' => $this->inline,
        ]);
    }
}
