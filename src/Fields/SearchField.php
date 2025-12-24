<?php

namespace Litepie\Form\Fields;

/**
 * Search Field
 */
class SearchField extends TextField
{
    protected ?string $searchUrl = null;
    protected int $minLength = 1;
    protected int $debounce = 300;
    protected bool $showResults = true;
    protected int $maxResults = 10;
    protected bool $liveSearch = false;
    protected bool $highlightMatch = true;
    protected string $noResultsText = 'No results found';
    protected ?string $searchIcon = null;
    protected bool $clearButton = true;

    protected function getFieldType(): string
    {
        return 'search';
    }

    public function searchUrl(string $url): self
    {
        $this->searchUrl = $url;
        return $this;
    }

    public function debounce(int $ms): self
    {
        $this->debounce = $ms;
        return $this;
    }

    public function showResults(bool $show = true): self
    {
        $this->showResults = $show;
        return $this;
    }

    public function maxResults(int $max): self
    {
        $this->maxResults = $max;
        return $this;
    }

    public function liveSearch(bool $live = true): self
    {
        $this->liveSearch = $live;
        return $this;
    }

    public function highlightMatch(bool $highlight = true): self
    {
        $this->highlightMatch = $highlight;
        return $this;
    }

    public function noResultsText(string $text): self
    {
        $this->noResultsText = $text;
        return $this;
    }

    public function searchIcon(string $icon): self
    {
        $this->searchIcon = $icon;
        return $this;
    }

    public function clearButton(bool $show = true): self
    {
        $this->clearButton = $show;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'searchUrl' => $this->searchUrl,
            'minLength' => $this->minLength,
            'debounce' => $this->debounce,
            'showResults' => $this->showResults,
            'maxResults' => $this->maxResults,
            'liveSearch' => $this->liveSearch,
            'highlightMatch' => $this->highlightMatch,
            'noResultsText' => $this->noResultsText,
            'searchIcon' => $this->searchIcon,
            'clearButton' => $this->clearButton,
        ]);
    }
}
