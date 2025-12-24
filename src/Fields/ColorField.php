<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Color field for color selection
 */
class ColorField extends Field
{
    protected string $format = 'hex';
    protected bool $showAlpha = false;
    protected bool $showInput = true;
    protected array $presets = [];
    protected array $swatches = [];
    protected bool $inline = false;

    protected function getFieldType(): string
    {
        return 'color';
    }

    public function format(string $format): self
    {
        $this->format = $format;
        return $this;
    }

    public function showAlpha(bool $show = true): self
    {
        $this->showAlpha = $show;
        return $this;
    }

    public function showInput(bool $show = true): self
    {
        $this->showInput = $show;
        return $this;
    }

    public function presets(array $presets): self
    {
        $this->presets = $presets;
        return $this;
    }

    public function swatches(array $swatches): self
    {
        $this->swatches = $swatches;
        return $this;
    }

    public function inline(bool $inline = true): self
    {
        $this->inline = $inline;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'format' => $this->format,
            'showAlpha' => $this->showAlpha,
            'showInput' => $this->showInput,
            'presets' => $this->presets,
            'swatches' => $this->swatches,
            'inline' => $this->inline,
        ]);
    }
}
