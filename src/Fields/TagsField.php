<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Tags input field with autocomplete and validation
 */
class TagsField extends Field
{
    /**
     * Allow custom tags not in options.
     */
    protected bool $allowCustom = true;

    /**
     * Maximum number of tags allowed.
     */
    protected ?int $maxTags = null;

    /**
     * Delimiter for tag separation.
     */
    protected string $delimiter = ',';

    /**
     * Minimum characters before showing suggestions.
     */
    protected int $minLength = 1;

    /**
     * Whether tag matching is case-sensitive.
     */
    protected bool $caseSensitive = false;

    /**
     * Whether duplicate tags are allowed.
     */
    protected bool $allowDuplicates = false;

    /**
     * Predefined tag suggestions.
     */
    protected array $suggestions = [];

    protected function getFieldType(): string
    {
        return 'tags';
    }

    /**
     * Set whether custom tags are allowed.
     */
    public function allowCustom(bool $allow = true): self
    {
        $this->allowCustom = $allow;
        return $this;
    }

    /**
     * Set maximum number of tags.
     */
    public function maxTags(?int $max): self
    {
        $this->maxTags = $max;
        return $this;
    }

    /**
     * Set tag delimiter.
     */
    public function delimiter(string $delimiter): self
    {
        $this->delimiter = $delimiter;
        return $this;
    }

    /**
     * Set minimum length for suggestions.
     */
    public function minLength(int $length): self
    {
        $this->minLength = $length;
        return $this;
    }

    /**
     * Set case sensitivity.
     */
    public function caseSensitive(bool $sensitive = true): self
    {
        $this->caseSensitive = $sensitive;
        return $this;
    }

    /**
     * Set whether duplicates are allowed.
     */
    public function allowDuplicates(bool $allow = true): self
    {
        $this->allowDuplicates = $allow;
        return $this;
    }

    /**
     * Set tag suggestions.
     */
    public function suggestions(array $suggestions): self
    {
        $this->suggestions = $suggestions;
        return $this;
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'allowCustom' => $this->allowCustom,
            'maxTags' => $this->maxTags,
            'delimiter' => $this->delimiter,
            'minLength' => $this->minLength,
            'caseSensitive' => $this->caseSensitive,
            'allowDuplicates' => $this->allowDuplicates,
            'suggestions' => $this->suggestions,
        ]);
    }
}
