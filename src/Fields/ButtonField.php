<?php

namespace Litepie\Form\Fields;

/**
 * Button Field
 */
class ButtonField extends TextField
{
    protected string $variant = 'primary';
    protected ?string $size = 'md';
    protected ?string $icon = null;
    protected string $iconPosition = 'left';
    protected bool $loading = false;
    protected ?string $loadingText = null;
    protected bool $block = false;
    protected bool $outline = false;
    protected ?string $action = null;
    protected ?string $confirmText = null;

    protected function getFieldType(): string
    {
        return 'button';
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

    public function block(bool $block = true): self
    {
        $this->block = $block;
        return $this;
    }

    public function outline(bool $outline = true): self
    {
        $this->outline = $outline;
        return $this;
    }

    public function action(string $action): self
    {
        $this->action = $action;
        return $this;
    }

    public function confirmText(string $text): self
    {
        $this->confirmText = $text;
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
            'block' => $this->block,
            'outline' => $this->outline,
            'action' => $this->action,
            'confirmText' => $this->confirmText,
        ]);
    }
}
