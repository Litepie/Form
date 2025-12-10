<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Divider/Separator Field
 * 
 * Visual separator for layout
 */
class DividerField extends Field
{
    protected string $style = 'solid'; // solid, dashed, dotted
    protected string $text = '';
    protected string $color = '#dee2e6';
    protected int $spacing = 3; // margin units
    
    /**
     * Get the field type.
     */
    protected function getFieldType(): string
    {
        return 'divider';
    }

    /**
     * Set divider style.
     */
    public function style(string $style): self
    {
        $this->style = $style;
        return $this;
    }

    /**
     * Get divider style.
     */
    public function getStyle(): string
    {
        return $this->style;
    }

    /**
     * Set divider text/label.
     */
    public function text(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Get divider text.
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Set divider color.
     */
    public function color(string $color): self
    {
        $this->color = $color;
        return $this;
    }

    /**
     * Get divider color.
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * Set spacing (margin).
     */
    public function spacing(int $spacing): self
    {
        $this->spacing = $spacing;
        return $this;
    }

    /**
     * Get spacing.
     */
    public function getSpacing(): int
    {
        return $this->spacing;
    }

    /**
     * Render the field.
     */
    public function render(): string
    {
        $marginClass = 'my-' . $this->spacing;
        
        if (!empty($this->text)) {
            return sprintf(
                '<div class="divider-with-text %s"><hr style="border-style: %s; border-color: %s;"><span class="divider-text">%s</span><hr style="border-style: %s; border-color: %s;"></div>',
                $marginClass,
                htmlspecialchars($this->style),
                htmlspecialchars($this->color),
                htmlspecialchars($this->text),
                htmlspecialchars($this->style),
                htmlspecialchars($this->color)
            );
        }
        
        return sprintf(
            '<hr class="%s" style="border-style: %s; border-color: %s;">',
            $marginClass,
            htmlspecialchars($this->style),
            htmlspecialchars($this->color)
        );
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'style' => $this->style,
            'text' => $this->text,
            'color' => $this->color,
            'spacing' => $this->spacing,
        ]);
    }
}
