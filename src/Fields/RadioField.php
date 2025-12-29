<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Radio button group field
 */
class RadioField extends Field
{
    /**
     * Display inline vs stacked.
     */
    protected bool $inline = false;

    /**
     * Number of columns for layout.
     */
    protected ?int $columns = null;

    /**
     * Button style appearance.
     */
    protected bool $buttonStyle = false;

    /**
     * Display as selectable cards.
     */
    protected bool $cardStyle = false;

    /**
     * Show descriptions under options.
     */
    protected array $descriptions = [];

    /**
     * Show icons with options.
     */
    protected array $icons = [];

    protected function getFieldType(): string
    {
        return 'radio';
    }

    /**
     * Set inline layout.
     */
    public function inline(bool $inline = true): self
    {
        $this->inline = $inline;
        return $this;
    }

    /**
     * Set number of columns.
     * Overrides parent to maintain compatibility.
     */
    public function columns(array|int $columns): self
    {
        if (is_int($columns)) {
            $this->columns = $columns;
        }
        return parent::columns($columns);
    }
    /**
     * Enable button style.
     */
    public function buttonStyle(bool $style = true): self
    {
        $this->buttonStyle = $style;
        return $this;
    }

    /**
     * Enable card style.
     */
    public function cardStyle(bool $style = true): self
    {
        $this->cardStyle = $style;
        return $this;
    }

    /**
     * Set descriptions for options.
     */
    public function descriptions(array $descriptions): self
    {
        $this->descriptions = $descriptions;
        return $this;
    }

    /**
     * Set icons for options.
     */
    public function icons(array $icons): self
    {
        $this->icons = $icons;
        return $this;
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'inline' => $this->inline,
            'columns' => $this->columns,
            'buttonStyle' => $this->buttonStyle,
            'cardStyle' => $this->cardStyle,
            'descriptions' => $this->descriptions,
            'icons' => $this->icons,
        ]);
    }
}
