<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * DateTime Local Field (HTML5)
 */
class DateTimeLocalField extends Field
{
    /**
     * DateTime format.
     */
    protected string $format = 'Y-m-d\TH:i';

    /**
     * Minimum datetime.
     */
    protected ?string $min = null;

    /**
     * Maximum datetime.
     */
    protected ?string $max = null;

    /**
     * Step in seconds.
     */
    protected ?int $step = null;

    /**
     * Show clear button.
     */
    protected bool $clearable = false;

    protected function getFieldType(): string
    {
        return 'datetime-local';
    }

    /**
     * Set step.
     */
    public function step(int $step): self
    {
        $this->step = $step;
        return $this;
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'format' => $this->format,
            'min' => $this->min,
            'max' => $this->max,
            'step' => $this->step,
            'clearable' => $this->clearable,
        ]);
    }
}
