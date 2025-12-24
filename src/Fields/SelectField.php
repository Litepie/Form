<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Select Field
 */
class SelectField extends Field
{
    /**
     * Allow multiple selection.
     */
    protected bool $multiple = false;

    /**
     * Enable search/filter.
     */
    protected bool $searchable = false;

    /**
     * Show clear button.
     */
    protected bool $clearable = false;

    /**
     * Maximum selections for multiple.
     */
    protected ?int $maxSelections = null;

    /**
     * Text when no options available.
     */
    protected ?string $emptyText = null;

    /**
     * Search input placeholder.
     */
    protected ?string $searchPlaceholder = null;

    /**
     * Allow creating new options.
     */
    protected bool $createOption = false;

    /**
     * Load options asynchronously.
     */
    protected bool $async = false;

    /**
     * URL to load options from.
     */
    protected ?string $loadUrl = null;

    /**
     * Close dropdown on selection.
     */
    protected bool $closeOnSelect = true;

    /**
     * Show selected count instead of tags.
     */
    protected bool $showSelectedCount = false;

    protected function getFieldType(): string
    {
        return 'select';
    }

    /**
     * Enable multiple selection.
     */
    public function multiple(bool $multiple = true): self
    {
        $this->multiple = $multiple;
        return $this;
    }

    /**
     * Enable searchable.
     */
    public function searchable(bool $searchable = true): self
    {
        $this->searchable = $searchable;
        return $this;
    }

    /**
     * Set maximum selections.
     */
    public function maxSelections(?int $max): self
    {
        $this->maxSelections = $max;
        return $this;
    }

    /**
     * Set empty text.
     */
    public function emptyText(string $text): self
    {
        $this->emptyText = $text;
        return $this;
    }

    /**
     * Set search placeholder.
     */
    public function searchPlaceholder(string $placeholder): self
    {
        $this->searchPlaceholder = $placeholder;
        return $this;
    }

    /**
     * Enable create option.
     */
    public function createOption(bool $create = true): self
    {
        $this->createOption = $create;
        return $this;
    }

    /**
     * Enable async loading.
     */
    public function async(bool $async = true): self
    {
        $this->async = $async;
        return $this;
    }

    /**
     * Set load URL.
     */
    public function loadUrl(string $url): self
    {
        $this->loadUrl = $url;
        $this->async = true;
        return $this;
    }

    /**
     * Set close on select.
     */
    public function closeOnSelect(bool $close = true): self
    {
        $this->closeOnSelect = $close;
        return $this;
    }

    /**
     * Show selected count.
     */
    public function showSelectedCount(bool $show = true): self
    {
        $this->showSelectedCount = $show;
        return $this;
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'multiple' => $this->multiple,
            'searchable' => $this->searchable,
            'clearable' => $this->clearable,
            'maxSelections' => $this->maxSelections,
            'emptyText' => $this->emptyText,
            'searchPlaceholder' => $this->searchPlaceholder,
            'createOption' => $this->createOption,
            'async' => $this->async,
            'loadUrl' => $this->loadUrl,
            'closeOnSelect' => $this->closeOnSelect,
            'showSelectedCount' => $this->showSelectedCount,
        ]);
    }
}
