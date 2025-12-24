<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Date Range Field
 */
class DateRangeField extends Field
{
    protected ?string $startDate = null;
    protected ?string $endDate = null;
    protected string $format = 'Y-m-d';
    protected string $separator = ' to ';
    protected ?string $minDate = null;
    protected ?string $maxDate = null;
    protected ?int $minDays = null;
    protected ?int $maxDays = null;
    protected bool $showPresets = false;
    protected array $presets = [];
    protected bool $singleCalendar = false;
    protected bool $clearable = true;
    protected bool $autoApply = false;

    protected function getFieldType(): string
    {
        return 'daterange';
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

    public function minDate(string $date): self
    {
        $this->minDate = $date;
        return $this;
    }

    public function maxDate(string $date): self
    {
        $this->maxDate = $date;
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
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'format' => $this->format,
            'separator' => $this->separator,
            'minDate' => $this->minDate,
            'maxDate' => $this->maxDate,
            'minDays' => $this->minDays,
            'maxDays' => $this->maxDays,
            'showPresets' => $this->showPresets,
            'presets' => $this->presets,
            'singleCalendar' => $this->singleCalendar,
            'clearable' => $this->clearable,
            'autoApply' => $this->autoApply,
        ]);
    }
}
