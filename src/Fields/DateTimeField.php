<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Datetime-local field
 */
class DateTimeField extends Field
{
    /**
     * DateTime format.
     */
    protected string $format = 'Y-m-d H:i:s';

    /**
     * Display format.
     */
    protected ?string $displayFormat = null;

    /**
     * Minimum datetime.
     */
    protected ?string $minDateTime = null;

    /**
     * Maximum datetime.
     */
    protected ?string $maxDateTime = null;

    /**
     * Enable time picker.
     */
    protected bool $enableTime = true;

    /**
     * Time format (12h/24h).
     */
    protected string $timeFormat = '24h';

    /**
     * Minute step.
     */
    protected int $minuteStep = 1;

    /**
     * Show seconds.
     */
    protected bool $showSeconds = false;

    /**
     * Show clear button.
     */
    protected bool $clearable = false;

    /**
     * Default hour.
     */
    protected ?int $defaultHour = null;

    /**
     * Default minute.
     */
    protected ?int $defaultMinute = null;

    protected function getFieldType(): string
    {
        return 'datetime';
    }

    /**
     * Set display format.
     */
    public function displayFormat(string $format): self
    {
        $this->displayFormat = $format;
        return $this;
    }

    /**
     * Set minimum datetime.
     */
    public function minDateTime(string $datetime): self
    {
        $this->minDateTime = $datetime;
        return $this;
    }

    /**
     * Set maximum datetime.
     */
    public function maxDateTime(string $datetime): self
    {
        $this->maxDateTime = $datetime;
        return $this;
    }

    /**
     * Enable time picker.
     */
    public function enableTime(bool $enable = true): self
    {
        $this->enableTime = $enable;
        return $this;
    }

    /**
     * Set time format.
     */
    public function timeFormat(string $format): self
    {
        $this->timeFormat = $format;
        return $this;
    }

    /**
     * Set minute step.
     */
    public function minuteStep(int $step): self
    {
        $this->minuteStep = $step;
        return $this;
    }

    /**
     * Show seconds.
     */
    public function showSeconds(bool $show = true): self
    {
        $this->showSeconds = $show;
        return $this;
    }

    /**
     * Set default time.
     */
    public function defaultTime(int $hour, int $minute = 0): self
    {
        $this->defaultHour = $hour;
        $this->defaultMinute = $minute;
        return $this;
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'format' => $this->format,
            'displayFormat' => $this->displayFormat,
            'minDateTime' => $this->minDateTime,
            'maxDateTime' => $this->maxDateTime,
            'enableTime' => $this->enableTime,
            'timeFormat' => $this->timeFormat,
            'minuteStep' => $this->minuteStep,
            'showSeconds' => $this->showSeconds,
            'clearable' => $this->clearable,
            'defaultHour' => $this->defaultHour,
            'defaultMinute' => $this->defaultMinute,
        ]);
    }
}
