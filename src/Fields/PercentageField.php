<?php

namespace Litepie\Form\Fields;

/**
 * Percentage Field
 * 
 * Specialized number field for percentages (0-100)
 */
class PercentageField extends NumberField
{
    protected int $decimals = 2;
    
    /**
     * Constructor.
     */
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setAttribute('min', 0);
        $this->setAttribute('max', 100);
        $this->setAttribute('step', 0.01);
    }
    
    /**
     * Get the field type.
     */
    protected function getFieldType(): string
    {
        return 'percentage';
    }

    /**
     * Set decimal places.
     */
    public function decimals(int $decimals): self
    {
        $this->decimals = $decimals;
        $this->setAttribute('step', 1 / pow(10, $decimals));
        return $this;
    }

    /**
     * Get decimal places.
     */
    public function getDecimals(): int
    {
        return $this->decimals;
    }

    /**
     * Render the field.
     */
    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<div class="input-group"><input type="number" name="%s" id="%s" value="%s" min="0" max="100" step="%s" %s><span class="input-group-text">%%</span></div>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? ''),
            $this->getAttribute('step', 0.01),
            $attributes
        );
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'decimals' => $this->decimals,
        ]);
    }
}
