<?php

namespace Litepie\Form\Fields;

/**
 * Submit Button Field
 */
class SubmitField extends TextField
{
    protected string $variant = 'primary';
    protected string $size = 'md';
    protected ?string $icon = null;
    protected string $iconPosition = 'left';
    protected bool $loading = false;
    protected ?string $loadingText = null;
    protected bool $disableOnSubmit = true;
    protected bool $block = false;

    protected function getFieldType(): string
    {
        return 'submit';
    }

    public function variant(string $variant): self
    {
        $this->variant = $variant;
        return $this;
    }

    public function size(string $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function icon(string $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    public function iconPosition(string $position): self
    {
        $this->iconPosition = $position;
        return $this;
    }

    public function loading(bool $loading = true): self
    {
        $this->loading = $loading;
        return $this;
    }

    public function loadingText(string $text): self
    {
        $this->loadingText = $text;
        return $this;
    }

    public function disableOnSubmit(bool $disable = true): self
    {
        $this->disableOnSubmit = $disable;
        return $this;
    }

    public function block(bool $block = true): self
    {
        $this->block = $block;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'variant' => $this->variant,
            'size' => $this->size,
            'icon' => $this->icon,
            'iconPosition' => $this->iconPosition,
            'loading' => $this->loading,
            'loadingText' => $this->loadingText,
            'disableOnSubmit' => $this->disableOnSubmit,
            'block' => $this->block,
        ]);
    }
}
