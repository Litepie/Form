<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Time Range Field
 */
class TimeRangeField extends Field
{
    protected ?string $startTime = null;
    protected ?string $endTime = null;
    protected string $format = 'H:i';
    protected string $separator = ' to ';
    protected ?string $minTime = null;
    protected ?string $maxTime = null;
    protected ?int $step = null; // minutes
    protected bool $use24Hour = true;
    protected bool $clearable = true;
    protected bool $autoApply = false;

    protected function getFieldType(): string
    {
        return 'timerange';
    }

    public function format(string $format): self
    {
        $this->format = $format;
        return $this;
    }

    public function separator(string $separator): self
    {
        $this->separator = $separator;
        return $this;
    }

    public function minTime(string $time): self
    {
        $this->minTime = $time;
        return $this;
    }

    public function maxTime(string $time): self
    {
        $this->maxTime = $time;
        return $this;
    }

    public function step(int $minutes): self
    {
        $this->step = $minutes;
        return $this;
    }

    public function use24Hour(bool $use24Hour = true): self
    {
        $this->use24Hour = $use24Hour;
        return $this;
    }

    public function clearable(bool $clearable = true): self
    {
        $this->clearable = $clearable;
        return $this;
    }

    public function autoApply(bool $auto = true): self
    {
        $this->autoApply = $auto;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'format' => $this->format,
            'separator' => $this->separator,
            'minTime' => $this->minTime,
            'maxTime' => $this->maxTime,
            'step' => $this->step,
            'use24Hour' => $this->use24Hour,
            'clearable' => $this->clearable,
            'autoApply' => $this->autoApply,
        ]);
    }
}
