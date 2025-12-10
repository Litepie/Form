<?php

namespace Litepie\Form\Fields;

/**
 * Autocomplete/Combobox Field
 * 
 * Text input with autocomplete suggestions
 */
class AutocompleteField extends TextField
{
    protected bool $allowCustom = true;
    protected int $minLength = 1;
    protected int $maxSuggestions = 10;
    protected string $source = ''; // URL or local data
    
    /**
     * Get the field type.
     */
    protected function getFieldType(): string
    {
        return 'autocomplete';
    }

    /**
     * Allow custom values not in options.
     */
    public function allowCustom(bool $allow = true): self
    {
        $this->allowCustom = $allow;
        return $this;
    }

    /**
     * Get allow custom setting.
     */
    public function isCustomAllowed(): bool
    {
        return $this->allowCustom;
    }

    /**
     * Set minimum length before showing suggestions.
     */
    public function minLength(int $length): self
    {
        $this->minLength = $length;
        return $this;
    }

    /**
     * Get minimum length.
     */
    public function getMinLength(): int
    {
        return $this->minLength;
    }

    /**
     * Set maximum suggestions to show.
     */
    public function maxSuggestions(int $max): self
    {
        $this->maxSuggestions = $max;
        return $this;
    }

    /**
     * Get maximum suggestions.
     */
    public function getMaxSuggestions(): int
    {
        return $this->maxSuggestions;
    }

    /**
     * Set data source (URL or 'local').
     */
    public function source(string $source): self
    {
        $this->source = $source;
        return $this;
    }

    /**
     * Get data source.
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * Render the field.
     */
    public function render(): string
    {
        $attributes = $this->buildAttributes();
        $optionsList = json_encode(array_keys($this->options));
        
        return sprintf(
            '<input type="text" name="%s" id="%s" value="%s" class="autocomplete-field" data-options=\'%s\' data-allow-custom="%s" data-min-length="%d" data-max-suggestions="%d" data-source="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? ''),
            $optionsList,
            $this->allowCustom ? 'true' : 'false',
            $this->minLength,
            $this->maxSuggestions,
            htmlspecialchars($this->source),
            $attributes
        );
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'allowCustom' => $this->allowCustom,
            'minLength' => $this->minLength,
            'maxSuggestions' => $this->maxSuggestions,
            'source' => $this->source,
        ]);
    }
}
