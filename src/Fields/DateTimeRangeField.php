<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * DateTime Range Field
 */
class DateTimeRangeField extends Field
{
    protected ?string $startDateTime = null;
    protected ?string $endDateTime = null;
    protected string $format = 'Y-m-d H:i';
    protected string $separator = ' to ';
    protected ?string $minDateTime = null;
    protected ?string $maxDateTime = null;
    protected ?int $minDays = null;
    protected ?int $maxDays = null;
    protected ?int $step = null; // minutes for time
    protected bool $use24Hour = true;
    protected bool $showSeconds = false;
    protected bool $showPresets = false;
    protected array $presets = [];
    protected bool $singleCalendar = false;
    protected bool $clearable = true;
    protected bool $autoApply = false;

    protected function getFieldType(): string
    {
        return 'datetimerange';
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

    public function minDateTime(string $dateTime): self
    {
        $this->minDateTime = $dateTime;
        return $this;
    }

    public function maxDateTime(string $dateTime): self
    {
        $this->maxDateTime = $dateTime;
        return $this;
    }

    public function minDays(int $days): self
    {
        $this->minDays = $days;
        return $this;
    }

    public function maxDays(int $days): self
    {
        $this->maxDays = $days;
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

    public function showSeconds(bool $show = true): self
    {
        $this->showSeconds = $show;
        return $this;
    }

    public function showPresets(bool $show = true): self
    {
        $this->showPresets = $show;
        return $this;
    }

    public function presets(array $presets): self
    {
        $this->presets = $presets;
        return $this;
    }

    public function singleCalendar(bool $single = true): self
    {
        $this->singleCalendar = $single;
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
            'startDateTime' => $this->startDateTime,
            'endDateTime' => $this->endDateTime,
            'format' => $this->format,
            'separator' => $this->separator,
            'minDateTime' => $this->minDateTime,
            'maxDateTime' => $this->maxDateTime,
            'minDays' => $this->minDays,
            'maxDays' => $this->maxDays,
            'step' => $this->step,
            'use24Hour' => $this->use24Hour,
            'showSeconds' => $this->showSeconds,
            'showPresets' => $this->showPresets,
            'presets' => $this->presets,
            'singleCalendar' => $this->singleCalendar,
            'clearable' => $this->clearable,
            'autoApply' => $this->autoApply,
        ]);
    }
}
