<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Advanced Time Picker Field with Full Feature Set
 * 
 * A comprehensive time input field with support for:
 * - Multiple time formats (12h/24h)
 * - Time range validation
 * - Custom step intervals
 * - Quick time presets
 * - Timezone support
 * - Multiple picker types (analog/digital/dropdown)
 * - Seconds and milliseconds
 * - Disabled/highlighted times
 * - Custom format display
 */
class TimeField extends Field
{
    /**
     * Time format (12h, 24h).
     */
    protected string $format = '24h';

    /**
     * Display format for showing time.
     */
    protected ?string $displayFormat = null;

    /**
     * Minimum allowed time.
     */
    protected ?string $minTime = null;

    /**
     * Maximum allowed time.
     */
    protected ?string $maxTime = null;

    /**
     * Time step in minutes.
     */
    protected ?int $step = null;

    /**
     * Show seconds.
     */
    protected bool $showSeconds = false;

    /**
     * Show milliseconds.
     */
    protected bool $showMilliseconds = false;

    /**
     * Picker type (analog, digital, dropdown, native).
     */
    protected string $pickerType = 'native';

    /**
     * Show AM/PM selector for 12h format.
     */
    protected bool $showPeriod = true;

    /**
     * Quick time presets.
     */
    protected array $presets = [];

    /**
     * Show "Now" button.
     */
    protected bool $showNow = false;

    /**
     * Show "Clear" button.
     */
    protected bool $clearable = false;

    /**
     * Disabled times (array of time strings).
     */
    protected array $disabledTimes = [];

    /**
     * Highlighted times (array of time strings).
     */
    protected array $highlightedTimes = [];

    /**
     * Timezone.
     */
    protected ?string $timezone = null;

    /**
     * Use UTC.
     */
    protected bool $useUtc = false;

    /**
     * Minute step (5, 10, 15, 30, 60).
     */
    protected int $minuteStep = 1;

    /**
     * Hour step.
     */
    protected int $hourStep = 1;

    /**
     * Second step.
     */
    protected int $secondStep = 1;

    /**
     * Prefix icon.
     */
    protected ?string $prefixIcon = null;

    /**
     * Suffix icon.
     */
    protected ?string $suffixIcon = null;

    /**
     * Inline picker (always visible).
     */
    protected bool $inline = false;

    /**
     * Picker position (top, bottom, auto).
     */
    protected string $position = 'auto';

    /**
     * Default time to show when opening picker.
     */
    protected ?string $defaultTime = null;

    /**
     * Show clock icon.
     */
    protected bool $showIcon = true;

    /**
     * Size (sm, md, lg).
     */
    protected ?string $size = null;

    /**
     * Loading state.
     */
    protected bool $loading = false;

    /**
     * Success state.
     */
    protected bool $success = false;

    /**
     * Error state.
     */
    protected bool $error = false;

    /**
     * Warning state.
     */
    protected bool $warning = false;

    /**
     * Scroll to current time when opening.
     */
    protected bool $scrollToTime = true;

    /**
     * Close on select.
     */
    protected bool $closeOnSelect = true;

    /**
     * Allow keyboard input.
     */
    protected bool $allowKeyboard = true;

    /**
     * Allow mouse wheel.
     */
    protected bool $allowMouseWheel = true;

    /**
     * Business hours only.
     */
    protected bool $businessHoursOnly = false;

    /**
     * Business hours start.
     */
    protected string $businessHoursStart = '09:00';

    /**
     * Business hours end.
     */
    protected string $businessHoursEnd = '17:00';

    /**
     * Get the field type.
     */
    protected function getFieldType(): string
    {
        return 'time';
    }

    /**
     * Set time format.
     */
    public function format(string $format): self
    {
        $this->format = $format;
        return $this;
    }

    /**
     * Use 12-hour format.
     */
    public function format12h(): self
    {
        $this->format = '12h';
        $this->showPeriod = true;
        return $this;
    }

    /**
     * Use 24-hour format.
     */
    public function format24h(): self
    {
        $this->format = '24h';
        $this->showPeriod = false;
        return $this;
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
     * Set minimum time.
     */
    public function minTime(string $time): self
    {
        $this->minTime = $time;
        return $this;
    }

    /**
     * Set maximum time.
     */
    public function maxTime(string $time): self
    {
        $this->maxTime = $time;
        return $this;
    }

    /**
     * Set time range.
     */
    public function range(string $min, string $max): self
    {
        $this->minTime = $min;
        $this->maxTime = $max;
        return $this;
    }

    /**
     * Set time step in minutes.
     */
    public function step(int $minutes): self
    {
        $this->step = $minutes;
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
     * Set hour step.
     */
    public function hourStep(int $step): self
    {
        $this->hourStep = $step;
        return $this;
    }

    /**
     * Set second step.
     */
    public function secondStep(int $step): self
    {
        $this->secondStep = $step;
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
     * Show milliseconds.
     */
    public function showMilliseconds(bool $show = true): self
    {
        $this->showMilliseconds = $show;
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
     * Use analog clock picker.
     */
    public function analog(): self
    {
        $this->pickerType = 'analog';
        return $this;
    }

    /**
     * Use digital picker.
     */
    public function digital(): self
    {
        $this->pickerType = 'digital';
        return $this;
    }

    /**
     * Use dropdown picker.
     */
    public function dropdown(): self
    {
        $this->pickerType = 'dropdown';
        return $this;
    }

    /**
     * Use native HTML5 time input.
     */
    public function native(): self
    {
        $this->pickerType = 'native';
        return $this;
    }

    /**
     * Show/hide AM/PM period selector.
     */
    public function showPeriod(bool $show = true): self
    {
        $this->showPeriod = $show;
        return $this;
    }

    /**
     * Add quick time presets.
     */
    public function presets(array $presets): self
    {
        $this->presets = $presets;
        return $this;
    }

    /**
     * Add common time presets.
     */
    public function commonPresets(): self
    {
        $this->presets = [
            'Morning' => '09:00',
            'Noon' => '12:00',
            'Afternoon' => '14:00',
            'Evening' => '18:00',
            'Night' => '21:00',
        ];
        return $this;
    }

    /**
     * Add business hours presets.
     */
    public function businessPresets(): self
    {
        $this->presets = [
            'Start of Day' => '09:00',
            'Mid Morning' => '10:30',
            'Lunch' => '12:00',
            'Afternoon' => '14:00',
            'End of Day' => '17:00',
        ];
        return $this;
    }

    /**
     * Show "Now" button.
     */
    public function showNow(bool $show = true): self
    {
        $this->showNow = $show;
        return $this;
    }

    /**
     * Make clearable.
     */
    public function clearable(bool $clearable = true): self
    {
        $this->clearable = $clearable;
        return $this;
    }

    /**
     * Set disabled times.
     */
    public function disabledTimes(array $times): self
    {
        $this->disabledTimes = $times;
        return $this;
    }

    /**
     * Disable time range.
     */
    public function disableRange(string $start, string $end): self
    {
        // This would need to be processed to generate all times in range
        $this->disabledTimes[] = ['start' => $start, 'end' => $end];
        return $this;
    }

    /**
     * Set highlighted times.
     */
    public function highlightedTimes(array $times): self
    {
        $this->highlightedTimes = $times;
        return $this;
    }

    /**
     * Set timezone.
     */
    public function timezone(string $timezone): self
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * Use UTC timezone.
     */
    public function useUtc(bool $use = true): self
    {
        $this->useUtc = $use;
        return $this;
    }

    /**
     * Set prefix icon.
     */
    public function prefixIcon(string $icon): self
    {
        $this->prefixIcon = $icon;
        return $this;
    }

    /**
     * Set suffix icon.
     */
    public function suffixIcon(string $icon): self
    {
        $this->suffixIcon = $icon;
        return $this;
    }

    /**
     * Show picker inline (always visible).
     */
    public function inline(bool $inline = true): self
    {
        $this->inline = $inline;
        return $this;
    }

    /**
     * Set picker position.
     */
    public function position(string $position): self
    {
        $this->position = $position;
        return $this;
    }

    /**
     * Set default time when picker opens.
     */
    public function defaultTime(string $time): self
    {
        $this->defaultTime = $time;
        return $this;
    }

    /**
     * Show/hide clock icon.
     */
    public function showIcon(bool $show = true): self
    {
        $this->showIcon = $show;
        return $this;
    }

    /**
     * Set field size.
     */
    public function size(string $size): self
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Set loading state.
     */
    public function loading(bool $loading = true): self
    {
        $this->loading = $loading;
        return $this;
    }

    /**
     * Set success state.
     */
    public function success(bool $success = true): self
    {
        $this->success = $success;
        return $this;
    }

    /**
     * Set error state.
     */
    public function error(bool $error = true): self
    {
        $this->error = $error;
        return $this;
    }

    /**
     * Set warning state.
     */
    public function warning(bool $warning = true): self
    {
        $this->warning = $warning;
        return $this;
    }

    /**
     * Scroll to current time when opening.
     */
    public function scrollToTime(bool $scroll = true): self
    {
        $this->scrollToTime = $scroll;
        return $this;
    }

    /**
     * Close picker on time select.
     */
    public function closeOnSelect(bool $close = true): self
    {
        $this->closeOnSelect = $close;
        return $this;
    }

    /**
     * Allow keyboard input.
     */
    public function allowKeyboard(bool $allow = true): self
    {
        $this->allowKeyboard = $allow;
        return $this;
    }

    /**
     * Allow mouse wheel to change time.
     */
    public function allowMouseWheel(bool $allow = true): self
    {
        $this->allowMouseWheel = $allow;
        return $this;
    }

    /**
     * Restrict to business hours only.
     */
    public function businessHoursOnly(bool $only = true, ?string $start = null, ?string $end = null): self
    {
        $this->businessHoursOnly = $only;
        
        if ($start !== null) {
            $this->businessHoursStart = $start;
        }
        
        if ($end !== null) {
            $this->businessHoursEnd = $end;
        }
        
        if ($only) {
            $this->minTime = $this->businessHoursStart;
            $this->maxTime = $this->businessHoursEnd;
        }
        
        return $this;
    }

    /**
     * Helper: Morning time (9:00 AM).
     */
    public function morning(): self
    {
        $this->value = '09:00';
        return $this;
    }

    /**
     * Helper: Noon time (12:00 PM).
     */
    public function noon(): self
    {
        $this->value = '12:00';
        return $this;
    }

    /**
     * Helper: Afternoon time (2:00 PM).
     */
    public function afternoon(): self
    {
        $this->value = '14:00';
        return $this;
    }

    /**
     * Helper: Evening time (6:00 PM).
     */
    public function evening(): self
    {
        $this->value = '18:00';
        return $this;
    }

    /**
     * Helper: Night time (9:00 PM).
     */
    public function night(): self
    {
        $this->value = '21:00';
        return $this;
    }

    /**
     * Helper: Set to current time.
     */
    public function now(): self
    {
        $this->value = date('H:i');
        return $this;
    }

    /**
     * Helper: 15-minute intervals.
     */
    public function intervals15(): self
    {
        $this->minuteStep = 15;
        return $this;
    }

    /**
     * Helper: 30-minute intervals.
     */
    public function intervals30(): self
    {
        $this->minuteStep = 30;
        return $this;
    }

    /**
     * Helper: Hour intervals.
     */
    public function hourlyIntervals(): self
    {
        $this->minuteStep = 60;
        return $this;
    }

    /**
     * Helper: Working hours (9 AM - 5 PM).
     */
    public function workingHours(): self
    {
        return $this->businessHoursOnly(true, '09:00', '17:00');
    }

    /**
     * Helper: Extended hours (6 AM - 10 PM).
     */
    public function extendedHours(): self
    {
        return $this->range('06:00', '22:00');
    }

    /**
     * Helper: Configure for appointment scheduling.
     */
    public function appointment(): self
    {
        return $this
            ->format12h()
            ->intervals15()
            ->businessHoursOnly(true)
            ->businessPresets()
            ->showNow();
    }

    /**
     * Helper: Configure for quick time entry.
     */
    public function quick(): self
    {
        return $this
            ->intervals30()
            ->commonPresets()
            ->clearable()
            ->showNow();
    }

    /**
     * Get all configuration as array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'format' => $this->format,
            'displayFormat' => $this->displayFormat,
            'minTime' => $this->minTime,
            'maxTime' => $this->maxTime,
            'step' => $this->step,
            'showSeconds' => $this->showSeconds,
            'showMilliseconds' => $this->showMilliseconds,
            'pickerType' => $this->pickerType,
            'showPeriod' => $this->showPeriod,
            'presets' => $this->presets,
            'showNow' => $this->showNow,
            'clearable' => $this->clearable,
            'disabledTimes' => $this->disabledTimes,
            'highlightedTimes' => $this->highlightedTimes,
            'timezone' => $this->timezone,
            'useUtc' => $this->useUtc,
            'minuteStep' => $this->minuteStep,
            'hourStep' => $this->hourStep,
            'secondStep' => $this->secondStep,
            'prefixIcon' => $this->prefixIcon,
            'suffixIcon' => $this->suffixIcon,
            'inline' => $this->inline,
            'position' => $this->position,
            'defaultTime' => $this->defaultTime,
            'showIcon' => $this->showIcon,
            'size' => $this->size,
            'loading' => $this->loading,
            'success' => $this->success,
            'error' => $this->error,
            'warning' => $this->warning,
            'scrollToTime' => $this->scrollToTime,
            'closeOnSelect' => $this->closeOnSelect,
            'allowKeyboard' => $this->allowKeyboard,
            'allowMouseWheel' => $this->allowMouseWheel,
            'businessHoursOnly' => $this->businessHoursOnly,
            'businessHoursStart' => $this->businessHoursStart,
            'businessHoursEnd' => $this->businessHoursEnd,
        ]);
    }
}
