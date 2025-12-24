<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Week picker field
 */
class WeekField extends Field
{
    /**
     * Week format.
     */
    protected string $format = 'Y-\\WW';

    /**
     * Minimum week.
     */
    protected ?string $minWeek = null;

    /**
     * Maximum week.
     */
    protected ?string $maxWeek = null;

    /**
     * Show clear button.
     */
    protected bool $clearable = false;

    /**
     * Year range.
     */
    protected ?string $yearRange = null;

    /**
     * First day of week (0=Sunday, 1=Monday).
     */
    protected int $firstDayOfWeek = 1;

    protected function getFieldType(): string
    {
        return 'week';
    }

    /**
     * Set minimum week.
     */
    public function minWeek(string $week): self
    {
        $this->minWeek = $week;
        return $this;
    }

    /**
     * Set maximum week.
     */
    public function maxWeek(string $week): self
    {
        $this->maxWeek = $week;
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
     * Set first day of week.
     */
    public function firstDayOfWeek(int $day): self
    {
        $this->firstDayOfWeek = $day;
        return $this;
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'format' => $this->format,
            'minWeek' => $this->minWeek,
            'maxWeek' => $this->maxWeek,
            'clearable' => $this->clearable,
            'yearRange' => $this->yearRange,
            'firstDayOfWeek' => $this->firstDayOfWeek,
        ]);
    }
}
