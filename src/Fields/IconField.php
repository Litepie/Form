<?php

namespace Litepie\Form\Fields;

/**
 * Icon Picker Field
 * 
 * For selecting icons from icon libraries
 */
class IconField extends TextField
{
    protected string $library = 'fontawesome'; // fontawesome, bootstrap-icons, heroicons, etc.
    protected array $availableIcons = [];
    protected bool $searchable = true;
    
    /**
     * Get the field type.
     */
    protected function getFieldType(): string
    {
        return 'icon';
    }

    /**
     * Set icon library.
     */
    public function library(string $library): self
    {
        $this->library = $library;
        return $this;
    }

    /**
     * Get icon library.
     */
    public function getLibrary(): string
    {
        return $this->library;
    }

    /**
     * Set available icons.
     */
    public function icons(array $icons): self
    {
        $this->availableIcons = $icons;
        return $this;
    }

    /**
     * Get available icons.
     */
    public function getIcons(): array
    {
        return $this->availableIcons;
    }

    /**
     * Enable/disable search.
     */
    public function searchable(bool $searchable = true): self
    {
        $this->searchable = $searchable;
        return $this;
    }

    /**
     * Get searchable setting.
     */
    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    /**
     * Render the field.
     */
    public function render(): string
    {
        $attributes = $this->buildAttributes();
        $iconsJson = json_encode($this->availableIcons);
        
        return sprintf(
            '<input type="text" name="%s" id="%s" value="%s" class="icon-picker" data-library="%s" data-icons=\'%s\' data-searchable="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? ''),
            htmlspecialchars($this->library),
            htmlspecialchars($iconsJson),
            $this->searchable ? 'true' : 'false',
            $attributes
        );
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'library' => $this->library,
            'availableIcons' => $this->availableIcons,
            'searchable' => $this->searchable,
        ]);
    }
}
