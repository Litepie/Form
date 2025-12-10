<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Key-Value Pair Field
 * 
 * For entering key-value pairs (like metadata)
 */
class KeyValueField extends Field
{
    protected bool $addable = true;
    protected bool $removable = true;
    protected string $keyLabel = 'Key';
    protected string $valueLabel = 'Value';
    protected string $addButtonText = 'Add Pair';
    
    /**
     * Get the field type.
     */
    protected function getFieldType(): string
    {
        return 'keyvalue';
    }

    /**
     * Enable/disable adding new pairs.
     */
    public function addable(bool $addable = true): self
    {
        $this->addable = $addable;
        return $this;
    }

    /**
     * Get addable setting.
     */
    public function isAddable(): bool
    {
        return $this->addable;
    }

    /**
     * Enable/disable removing pairs.
     */
    public function removable(bool $removable = true): self
    {
        $this->removable = $removable;
        return $this;
    }

    /**
     * Get removable setting.
     */
    public function isRemovable(): bool
    {
        return $this->removable;
    }

    /**
     * Set key label.
     */
    public function keyLabel(string $label): self
    {
        $this->keyLabel = $label;
        return $this;
    }

    /**
     * Get key label.
     */
    public function getKeyLabel(): string
    {
        return $this->keyLabel;
    }

    /**
     * Set value label.
     */
    public function valueLabel(string $label): self
    {
        $this->valueLabel = $label;
        return $this;
    }

    /**
     * Get value label.
     */
    public function getValueLabel(): string
    {
        return $this->valueLabel;
    }

    /**
     * Set add button text.
     */
    public function addButtonText(string $text): self
    {
        $this->addButtonText = $text;
        return $this;
    }

    /**
     * Get add button text.
     */
    public function getAddButtonText(): string
    {
        return $this->addButtonText;
    }

    /**
     * Render the field.
     */
    public function render(): string
    {
        $value = (array)($this->value ?? []);
        
        return sprintf(
            '<div class="keyvalue-field" data-name="%s" data-addable="%s" data-removable="%s" data-key-label="%s" data-value-label="%s" data-add-text="%s" data-value=\'%s\'></div>',
            htmlspecialchars($this->name),
            $this->addable ? 'true' : 'false',
            $this->removable ? 'true' : 'false',
            htmlspecialchars($this->keyLabel),
            htmlspecialchars($this->valueLabel),
            htmlspecialchars($this->addButtonText),
            htmlspecialchars(json_encode($value))
        );
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'addable' => $this->addable,
            'removable' => $this->removable,
            'keyLabel' => $this->keyLabel,
            'valueLabel' => $this->valueLabel,
            'addButtonText' => $this->addButtonText,
        ]);
    }
}
