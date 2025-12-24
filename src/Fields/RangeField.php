<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Range/Slider field for numeric ranges
 */
class RangeField extends Field
{
    protected float $min = 0;
    protected float $max = 100;
    protected float $step = 1;
    protected bool $showValue = true;
    protected bool $showLabels = false;
    protected bool $showTicks = false;
    protected ?int $tickInterval = null;
    protected ?string $tooltip = null;
    protected bool $dual = false;
    protected bool $vertical = false;
    protected ?string $color = null;

    protected function getFieldType(): string
    {
        return 'range';
    }

    public function step(float $step): self
    {
        $this->step = $step;
        return $this;
    }

    public function showValue(bool $show = true): self
    {
        $this->showValue = $show;
        return $this;
    }

    public function showLabels(bool $show = true): self
    {
        $this->showLabels = $show;
        return $this;
    }

    public function showTicks(bool $show = true): self
    {
        $this->showTicks = $show;
        return $this;
    }

    public function tickInterval(int $interval): self
    {
        $this->tickInterval = $interval;
        return $this;
    }

    public function tooltip(bool $show = true): self
    {
        $this->tooltip = $show;
        return $this;
    }

    public function dual(bool $dual = true): self
    {
        $this->dual = $dual;
        return $this;
    }

    public function vertical(bool $vertical = true): self
    {
        $this->vertical = $vertical;
        return $this;
    }

    public function color(string $color): self
    {
        $this->color = $color;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'min' => $this->min,
            'max' => $this->max,
            'step' => $this->step,
            'showValue' => $this->showValue,
            'showLabels' => $this->showLabels,
            'showTicks' => $this->showTicks,
            'tickInterval' => $this->tickInterval,
            'tooltip' => $this->tooltip,
            'dual' => $this->dual,
            'vertical' => $this->vertical,
            'color' => $this->color,
        ]);
    }
}
