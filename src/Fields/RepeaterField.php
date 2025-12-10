<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Repeater/Array Field
 * 
 * Dynamic field repetition for arrays of data
 */
class RepeaterField extends Field
{
    protected array $schema = [];
    protected int $min = 0;
    protected int $max = 999;
    protected bool $sortable = true;
    protected string $addButtonText = 'Add Item';
    protected string $removeButtonText = 'Remove';
    
    /**
     * Get the field type.
     */
    protected function getFieldType(): string
    {
        return 'repeater';
    }

    /**
     * Set the field schema (array of Field instances).
     */
    public function schema(array $fields): self
    {
        $this->schema = $fields;
        return $this;
    }

    /**
     * Get the schema.
     */
    public function getSchema(): array
    {
        return $this->schema;
    }

    /**
     * Set minimum items.
     */
    public function min(int $min): self
    {
        $this->min = $min;
        return $this;
    }

    /**
     * Get minimum items.
     */
    public function getMin(): int
    {
        return $this->min;
    }

    /**
     * Set maximum items.
     */
    public function max(int $max): self
    {
        $this->max = $max;
        return $this;
    }

    /**
     * Get maximum items.
     */
    public function getMax(): int
    {
        return $this->max;
    }

    /**
     * Enable/disable sorting.
     */
    public function sortable(bool $sortable = true): self
    {
        $this->sortable = $sortable;
        return $this;
    }

    /**
     * Get sortable setting.
     */
    public function isSortable(): bool
    {
        return $this->sortable;
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
     * Set remove button text.
     */
    public function removeButtonText(string $text): self
    {
        $this->removeButtonText = $text;
        return $this;
    }

    /**
     * Get remove button text.
     */
    public function getRemoveButtonText(): string
    {
        return $this->removeButtonText;
    }

    /**
     * Render the field.
     */
    public function render(): string
    {
        $schemaJson = json_encode(array_map(fn($field) => $field->toArray(), $this->schema));
        
        return sprintf(
            '<div class="repeater-field" data-name="%s" data-min="%d" data-max="%d" data-sortable="%s" data-schema=\'%s\' data-add-text="%s" data-remove-text="%s"></div>',
            htmlspecialchars($this->name),
            $this->min,
            $this->max,
            $this->sortable ? 'true' : 'false',
            htmlspecialchars($schemaJson),
            htmlspecialchars($this->addButtonText),
            htmlspecialchars($this->removeButtonText)
        );
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'schema' => array_map(fn($field) => $field->toArray(), $this->schema),
            'min' => $this->min,
            'max' => $this->max,
            'sortable' => $this->sortable,
            'addButtonText' => $this->addButtonText,
            'removeButtonText' => $this->removeButtonText,
        ]);
    }
}
