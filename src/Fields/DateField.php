<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Date Field
 */
class DateField extends Field
{
    /**
     * Date format.
     */
    protected string $format = 'Y-m-d';

    /**
     * Display format for user.
     */
    protected ?string $displayFormat = null;

    /**
     * Minimum date allowed.
     */
    protected ?string $minDate = null;

    /**
     * Maximum date allowed.
     */
    protected ?string $maxDate = null;

    /**
     * Disabled dates.
     */
    protected array $disabledDates = [];

    /**
     * Disabled days of week (0-6).
     */
    protected array $disabledDays = [];

    /**
     * Highlighted dates.
     */
    protected array $highlightedDates = [];

    /**
     * Show week numbers.
     */
    protected bool $showWeekNumbers = false;

    /**
     * First day of week (0=Sunday, 1=Monday).
     */
    protected int $firstDayOfWeek = 0;

    /**
     * Year range for picker.
     */
    protected ?string $yearRange = null;

    /**
     * Show today button.
     */
    protected bool $showToday = true;

    /**
     * Show clear button.
     */
    protected bool $clearable = false;

    /**
     * Picker type.
     */
    protected string $pickerType = 'calendar';

    protected function getFieldType(): string
    {
        return 'date';
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
     * Set minimum date.
     */
    public function minDate(string $date): self
    {
        $this->minDate = $date;
        return $this;
    }

    /**
     * Set maximum date.
     */
    public function maxDate(string $date): self
    {
        $this->maxDate = $date;
        return $this;
    }

    /**
     * Set disabled dates.
     */
    public function disabledDates(array $dates): self
    {
        $this->disabledDates = $dates;
        return $this;
    }

    /**
     * Set disabled days.
     */
    public function disabledDays(array $days): self
    {
        $this->disabledDays = $days;
        return $this;
    }

    /**
     * Set highlighted dates.
     */
    public function highlightedDates(array $dates): self
    {
        $this->highlightedDates = $dates;
        return $this;
    }

    /**
     * Show week numbers.
     */
    public function showWeekNumbers(bool $show = true): self
    {
        $this->showWeekNumbers = $show;
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
     * Set year range.
     */
    public function yearRange(string $range): self
    {
        $this->yearRange = $range;
        return $this;
    }

    /**
     * Show today button.
     */
    public function showToday(bool $show = true): self
    {
        $this->showToday = $show;
        return $this;
    }

    /**
     * Set picker type.
     */
    public function pickerType(string $type): self
    {
        $this->pickerType = $type;
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
            'minDate' => $this->minDate,
            'maxDate' => $this->maxDate,
            'disabledDates' => $this->disabledDates,
            'disabledDays' => $this->disabledDays,
            'highlightedDates' => $this->highlightedDates,
            'showWeekNumbers' => $this->showWeekNumbers,
            'firstDayOfWeek' => $this->firstDayOfWeek,
            'yearRange' => $this->yearRange,
            'showToday' => $this->showToday,
            'clearable' => $this->clearable,
            'pickerType' => $this->pickerType,
        ]);
    }
}
