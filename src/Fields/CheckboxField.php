<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Checkbox group field
 */
class CheckboxField extends Field
{
    /**
     * Default checked state.
     */
    protected bool $checked = false;

    /**
     * Indeterminate state.
     */
    protected bool $indeterminate = false;

    /**
     * Label position (left or right).
     */
    protected string $labelPosition = 'right';

    /**
     * Value when checked.
     */
    protected mixed $checkedValue = true;

    /**
     * Value when unchecked.
     */
    protected mixed $uncheckedValue = false;

    protected function getFieldType(): string
    {
        return 'checkbox';
    }

    /**
     * Set checked state.
     */
    public function checked(bool $checked = true): self
    {
        $this->checked = $checked;
        $this->value = $checked ? $this->checkedValue : $this->uncheckedValue;
        return $this;
    }

    /**
     * Set indeterminate state.
     */
    public function indeterminate(bool $indeterminate = true): self
    {
        $this->indeterminate = $indeterminate;
        return $this;
    }

    /**
     * Set label position.
     */
    public function labelPosition(string $position): self
    {
        $this->labelPosition = $position;
        return $this;
    }

    /**
     * Set checked and unchecked values.
     */
    public function values(mixed $checkedValue, mixed $uncheckedValue): self
    {
        $this->checkedValue = $checkedValue;
        $this->uncheckedValue = $uncheckedValue;
        return $this;
    }

    /**
     * Get checked value.
     */
    public function getCheckedValue(): mixed
    {
        return $this->checkedValue;
    }

    /**
     * Get unchecked value.
     */
    public function getUncheckedValue(): mixed
    {
        return $this->uncheckedValue;
    }

    /**
     * Check if checked.
     */
    public function isChecked(): bool
    {
        return $this->checked || $this->value == $this->checkedValue;
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'checked' => $this->isChecked(),
            'indeterminate' => $this->indeterminate,
            'labelPosition' => $this->labelPosition,
            'checkedValue' => $this->checkedValue,
            'uncheckedValue' => $this->uncheckedValue,
        ]);
    }
}
