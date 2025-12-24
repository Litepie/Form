<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Month picker field
 */
class MonthField extends Field
{
    /**
     * Month format.
     */
    protected string $format = 'Y-m';

    /**
     * Minimum month.
     */
    protected ?string $minMonth = null;

    /**
     * Maximum month.
     */
    protected ?string $maxMonth = null;

    /**
     * Show clear button.
     */
    protected bool $clearable = false;

    /**
     * Year range.
     */
    protected ?string $yearRange = null;

    protected function getFieldType(): string
    {
        return 'month';
    }

    /**
     * Set minimum month.
     */
    public function minMonth(string $month): self
    {
        $this->minMonth = $month;
        return $this;
    }

    /**
     * Set maximum month.
     */
    public function maxMonth(string $month): self
    {
        $this->maxMonth = $month;
        return $this;
    }

    /**
     * Set year range.
     */
    public function yearRange(string $range): self
    {
        $this->yearRange = $range;
        return $this;
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'format' => $this->format,
            'minMonth' => $this->minMonth,
            'maxMonth' => $this->maxMonth,
            'clearable' => $this->clearable,
            'yearRange' => $this->yearRange,
        ]);
    }
}
