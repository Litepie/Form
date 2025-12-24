<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Toggle/Switch Field
 * 
 * A visual on/off switch (different from checkbox)
 */
class ToggleField extends Field
{
    protected string $onLabel = 'On';
    protected string $offLabel = 'Off';
    
    /**
     * Get the field type.
     */
    protected function getFieldType(): string
    {
        return 'toggle';
    }

    /**
     * Set the "on" label.
     */
    public function onLabel(string $label): self
    {
        $this->onLabel = $label;
        return $this;
    }

    /**
     * Get the "on" label.
     */
    public function getOnLabel(): string
    {
        return $this->onLabel;
    }

    /**
     * Set the "off" label.
     */
    public function offLabel(string $label): self
    {
        $this->offLabel = $label;
        return $this;
    }

    /**
     * Get the "off" label.
     */
    public function getOffLabel(): string
    {
        return $this->offLabel;
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'onLabel' => $this->onLabel,
            'offLabel' => $this->offLabel,
        ]);
    }
}
