<?php

namespace Litepie\Form\Fields;

/**
 * Reset Button Field
 */
class ResetField extends TextField
{
    protected string $variant = 'secondary';
    protected string $size = 'md';
    protected ?string $icon = null;
    protected ?string $confirmText = null;

    protected function getFieldType(): string
    {
        return 'reset';
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
            'confirmText' => $this->confirmText,
        ]);
    }
}
