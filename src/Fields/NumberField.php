<?php

namespace Litepie\Form\Fields;

/**
 * Number Field
 */
class NumberField extends TextField
{
    protected ?float $min = null;
    protected ?float $max = null;
    protected float $step = 1;
    protected int $decimals = 0;
    protected bool $allowNegative = true;
    protected bool $showControls = true;
    protected string $thousandsSeparator = ',';
    protected string $decimalSeparator = '.';
    protected ?string $suffix = null;
    protected ?string $prefix = null;

    protected function getFieldType(): string
    {
        return 'number';
    }

    public function step(float $step): self
    {
        $this->step = $step;
        return $this;
    }

    public function decimals(int $decimals): self
    {
        $this->decimals = $decimals;
        return $this;
    }

    public function allowNegative(bool $allow = true): self
    {
        $this->allowNegative = $allow;
        return $this;
    }

    public function showControls(bool $show = true): self
    {
        $this->showControls = $show;
        return $this;
    }

    public function thousandsSeparator(string $separator): self
    {
        $this->thousandsSeparator = $separator;
        return $this;
    }

    public function decimalSeparator(string $separator): self
    {
        $this->decimalSeparator = $separator;
        return $this;
    }

    public function suffix(string $suffix): self
    {
        $this->suffix = $suffix;
        return $this;
    }

    public function prefix(string $prefix): self
    {
        $this->prefix = $prefix;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'min' => $this->min,
            'max' => $this->max,
            'step' => $this->step,
            'decimals' => $this->decimals,
            'allowNegative' => $this->allowNegative,
            'showControls' => $this->showControls,
            'thousandsSeparator' => $this->thousandsSeparator,
            'decimalSeparator' => $this->decimalSeparator,
            'suffix' => $this->suffix,
            'prefix' => $this->prefix,
        ]);
    }
}
