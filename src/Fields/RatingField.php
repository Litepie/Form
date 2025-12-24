<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Rating/Stars field
 */
class RatingField extends Field
{
    protected int $max = 5;
    protected string $icon = 'star';
    protected bool $allowHalf = false;
    protected bool $allowClear = false;
    protected bool $showCount = false;
    protected string $size = 'md';
    protected ?string $color = null;
    protected ?string $emptyColor = null;
    protected bool $readonly = false;
    protected array $tooltips = [];

    protected function getFieldType(): string
    {
        return 'rating';
    }

    public function icon(string $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    public function allowHalf(bool $allow = true): self
    {
        $this->allowHalf = $allow;
        return $this;
    }

    public function allowClear(bool $allow = true): self
    {
        $this->allowClear = $allow;
        return $this;
    }

    public function showCount(bool $show = true): self
    {
        $this->showCount = $show;
        return $this;
    }

    public function size(string $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function color(string $color): self
    {
        $this->color = $color;
        return $this;
    }

    public function emptyColor(string $color): self
    {
        $this->emptyColor = $color;
        return $this;
    }

    public function readonly(bool $readonly = true): self
    {
        $this->readonly = $readonly;
        return $this;
    }

    public function tooltips(array $tooltips): self
    {
        $this->tooltips = $tooltips;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'max' => $this->max,
            'icon' => $this->icon,
            'allowHalf' => $this->allowHalf,
            'allowClear' => $this->allowClear,
            'showCount' => $this->showCount,
            'size' => $this->size,
            'color' => $this->color,
            'emptyColor' => $this->emptyColor,
            'readonly' => $this->readonly,
            'tooltips' => $this->tooltips,
        ]);
    }
}
