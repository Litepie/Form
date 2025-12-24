<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Advanced Text Field with Full Feature Set
 * 
 * A comprehensive text input field with support for:
 * - Input masking
 * - Character counter
 * - Prefix/suffix icons and text
 * - Autocomplete
 * - Pattern validation
 * - Input transformation
 * - Debounce
 * - Real-time validation
 * - Copy/paste handling
 * - Custom formatting
 */
class TextField extends Field
{
    /**
     * Input mask pattern (e.g., '(999) 999-9999' for phone).
     */
    protected ?string $mask = null;

    /**
     * Maximum character length.
     */
    protected ?int $maxLength = null;

    /**
     * Minimum character length.
     */
    protected ?int $minLength = null;

    /**
     * Show character counter.
     */
    protected bool $showCounter = false;

    /**
     * Prefix text or icon.
     */
    protected ?string $prefix = null;

    /**
     * Suffix text or icon.
     */
    protected ?string $suffix = null;

    /**
     * Prefix icon class (e.g., 'fa fa-user').
     */
    protected ?string $prefixIcon = null;

    /**
     * Suffix icon class (e.g., 'fa fa-check').
     */
    protected ?string $suffixIcon = null;

    /**
     * HTML5 pattern attribute.
     */
    protected ?string $pattern = null;

    /**
     * Input mode (text, numeric, tel, email, url, search, decimal).
     */
    protected ?string $inputMode = null;

    /**
     * Autocomplete attribute.
     */
    protected ?string $autocomplete = null;

    /**
     * Autocapitalize (off, none, on, sentences, words, characters).
     */
    protected ?string $autocapitalize = null;

    /**
     * Spellcheck.
     */
    protected ?bool $spellcheck = null;

    /**
     * Text transformation (uppercase, lowercase, capitalize).
     */
    protected ?string $transform = null;

    /**
     * Debounce delay in milliseconds.
     */
    protected ?int $debounce = null;

    /**
     * Show clear button.
     */
    protected bool $clearable = false;

    /**
     * Show copy button.
     */
    protected bool $copyable = false;

    /**
     * Allow paste.
     */
    protected bool $allowPaste = true;

    /**
     * Strip whitespace on blur.
     */
    protected bool $trimOnBlur = true;

    /**
     * Format value on blur (callback).
     */
    protected ?\Closure $formatOnBlur = null;

    /**
     * Real-time validation callback.
     */
    protected ?\Closure $liveValidation = null;

    /**
     * Custom input filter callback.
     */
    protected ?\Closure $inputFilter = null;

    /**
     * Allowed characters regex.
     */
    protected ?string $allowedChars = null;

    /**
     * Disallowed characters regex.
     */
    protected ?string $disallowedChars = null;

    /**
     * Input size (sm, md, lg).
     */
    protected ?string $size = null;

    /**
     * Show password toggle (for password-like inputs).
     */
    protected bool $toggleable = false;

    /**
     * Floating label.
     */
    protected bool $floatingLabel = false;

    /**
     * Loading state.
     */
    protected bool $loading = false;

    /**
     * Success state.
     */
    protected bool $success = false;

    /**
     * Error state.
     */
    protected bool $error = false;

    /**
     * Warning state.
     */
    protected bool $warning = false;

    /**
     * Icon click callback.
     */
    protected ?string $iconClick = null;

    /**
     * Get the field type.
     */
    protected function getFieldType(): string
    {
        return 'text';
    }

    /**
     * Set input mask pattern.
     */
    public function mask(string $mask): self
    {
        $this->mask = $mask;
        return $this;
    }

    /**
     * Set maximum length and optionally show counter.
     */
    public function maxLength(int $length, bool $showCounter = false): self
    {
        $this->maxLength = $length;
        $this->showCounter = $showCounter;
        return $this;
    }

    /**
     * Set minimum length.
     */
    public function minLength(int $length): self
    {
        $this->minLength = $length;
        return $this;
    }

    /**
     * Show character counter.
     */
    public function counter(bool $show = true): self
    {
        $this->showCounter = $show;
        return $this;
    }

    /**
     * Set prefix text.
     */
    public function prefix(string $prefix): self
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * Set suffix text.
     */
    public function suffix(string $suffix): self
    {
        $this->suffix = $suffix;
        return $this;
    }

    /**
     * Set prefix icon.
     */
    public function prefixIcon(string $icon): self
    {
        $this->prefixIcon = $icon;
        return $this;
    }

    /**
     * Set suffix icon.
     */
    public function suffixIcon(string $icon): self
    {
        $this->suffixIcon = $icon;
        return $this;
    }

    /**
     * Set HTML5 pattern.
     */
    public function pattern(string $pattern): self
    {
        $this->pattern = $pattern;
        return $this;
    }

    /**
     * Set input mode.
     */
    public function inputMode(string $mode): self
    {
        $this->inputMode = $mode;
        return $this;
    }

    /**
     * Set autocomplete.
     */
    public function autocomplete(string $value): self
    {
        $this->autocomplete = $value;
        return $this;
    }

    /**
     * Set autocapitalize.
     */
    public function autocapitalize(string $value): self
    {
        $this->autocapitalize = $value;
        return $this;
    }

    /**
     * Set spellcheck.
     */
    public function spellcheck(bool $enabled = true): self
    {
        $this->spellcheck = $enabled;
        return $this;
    }

    /**
     * Set text transformation.
     */
    public function transform(string $transform): self
    {
        $this->transform = $transform;
        return $this;
    }

    /**
     * Set debounce delay.
     */
    public function debounce(int $milliseconds): self
    {
        $this->debounce = $milliseconds;
        return $this;
    }

    /**
     * Make field clearable.
     */
    public function clearable(bool $clearable = true): self
    {
        $this->clearable = $clearable;
        return $this;
    }

    /**
     * Make field copyable.
     */
    public function copyable(bool $copyable = true): self
    {
        $this->copyable = $copyable;
        return $this;
    }

    /**
     * Allow/disallow paste.
     */
    public function allowPaste(bool $allow = true): self
    {
        $this->allowPaste = $allow;
        return $this;
    }

    /**
     * Trim whitespace on blur.
     */
    public function trimOnBlur(bool $trim = true): self
    {
        $this->trimOnBlur = $trim;
        return $this;
    }

    /**
     * Set format callback for blur event.
     */
    public function formatOnBlur(\Closure $callback): self
    {
        $this->formatOnBlur = $callback;
        return $this;
    }

    /**
     * Set live validation callback.
     */
    public function liveValidation(\Closure $callback): self
    {
        $this->liveValidation = $callback;
        return $this;
    }

    /**
     * Set input filter callback.
     */
    public function inputFilter(\Closure $callback): self
    {
        $this->inputFilter = $callback;
        return $this;
    }

    /**
     * Set allowed characters regex.
     */
    public function allowedChars(string $regex): self
    {
        $this->allowedChars = $regex;
        return $this;
    }

    /**
     * Set disallowed characters regex.
     */
    public function disallowedChars(string $regex): self
    {
        $this->disallowedChars = $regex;
        return $this;
    }

    /**
     * Set input size.
     */
    public function size(string $size): self
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Make toggleable (show/hide content).
     */
    public function toggleable(bool $toggleable = true): self
    {
        $this->toggleable = $toggleable;
        return $this;
    }

    /**
     * Use floating label.
     */
    public function floatingLabel(bool $floating = true): self
    {
        $this->floatingLabel = $floating;
        return $this;
    }

    /**
     * Set loading state.
     */
    public function loading(bool $loading = true): self
    {
        $this->loading = $loading;
        return $this;
    }

    /**
     * Set success state.
     */
    public function success(bool $success = true): self
    {
        $this->success = $success;
        return $this;
    }

    /**
     * Set error state.
     */
    public function error(bool $error = true): self
    {
        $this->error = $error;
        return $this;
    }

    /**
     * Set warning state.
     */
    public function warning(bool $warning = true): self
    {
        $this->warning = $warning;
        return $this;
    }

    /**
     * Set icon click handler.
     */
    public function iconClick(string $handler): self
    {
        $this->iconClick = $handler;
        return $this;
    }

    /**
     * Helper method to allow only numbers.
     */
    public function onlyNumbers(): self
    {
        $this->allowedChars = '[0-9]';
        $this->inputMode = 'numeric';
        return $this;
    }

    /**
     * Helper method to allow only letters.
     */
    public function onlyLetters(): self
    {
        $this->allowedChars = '[a-zA-Z]';
        return $this;
    }

    /**
     * Helper method to allow only alphanumeric.
     */
    public function onlyAlphanumeric(): self
    {
        $this->allowedChars = '[a-zA-Z0-9]';
        return $this;
    }

    /**
     * Helper method for phone number.
     */
    public function phone(): self
    {
        $this->mask = '(999) 999-9999';
        $this->inputMode = 'tel';
        $this->autocomplete = 'tel';
        return $this;
    }

    /**
     * Helper method for credit card.
     */
    public function creditCard(): self
    {
        $this->mask = '9999 9999 9999 9999';
        $this->inputMode = 'numeric';
        $this->autocomplete = 'cc-number';
        return $this;
    }

    /**
     * Helper method for date format.
     */
    public function dateFormat(string $format = 'MM/DD/YYYY'): self
    {
        $this->mask = str_replace(['M', 'D', 'Y'], '9', $format);
        $this->placeholder = $format;
        return $this;
    }

    /**
     * Helper method for time format.
     */
    public function timeFormat(string $format = 'HH:MM'): self
    {
        $this->mask = str_replace(['H', 'M'], '9', $format);
        $this->placeholder = $format;
        return $this;
    }

    /**
     * Helper method for currency.
     */
    public function currency(string $symbol = '$'): self
    {
        $this->prefix = $symbol;
        $this->inputMode = 'decimal';
        $this->allowedChars = '[0-9.]';
        return $this;
    }

    /**
     * Helper method for percentage.
     */
    public function percentage(): self
    {
        $this->suffix = '%';
        $this->inputMode = 'decimal';
        $this->allowedChars = '[0-9.]';
        $this->attribute('min', 0);
        $this->attribute('max', 100);
        return $this;
    }

    /**
     * Helper method for slug.
     */
    public function slug(): self
    {
        $this->transform = 'lowercase';
        $this->allowedChars = '[a-z0-9-]';
        $this->pattern = '^[a-z0-9]+(?:-[a-z0-9]+)*$';
        return $this;
    }

    /**
     * Helper method for username.
     */
    public function username(): self
    {
        $this->allowedChars = '[a-zA-Z0-9_-]';
        $this->pattern = '^[a-zA-Z0-9_-]{3,}$';
        $this->autocomplete = 'username';
        return $this;
    }

    /**
     * Get all configuration as array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'mask' => $this->mask,
            'maxLength' => $this->maxLength,
            'minLength' => $this->minLength,
            'showCounter' => $this->showCounter,
            'prefix' => $this->prefix,
            'suffix' => $this->suffix,
            'prefixIcon' => $this->prefixIcon,
            'suffixIcon' => $this->suffixIcon,
            'pattern' => $this->pattern,
            'inputMode' => $this->inputMode,
            'autocomplete' => $this->autocomplete,
            'autocapitalize' => $this->autocapitalize,
            'spellcheck' => $this->spellcheck,
            'transform' => $this->transform,
            'debounce' => $this->debounce,
            'clearable' => $this->clearable,
            'copyable' => $this->copyable,
            'allowPaste' => $this->allowPaste,
            'trimOnBlur' => $this->trimOnBlur,
            'allowedChars' => $this->allowedChars,
            'disallowedChars' => $this->disallowedChars,
            'size' => $this->size,
            'toggleable' => $this->toggleable,
            'floatingLabel' => $this->floatingLabel,
            'loading' => $this->loading,
            'success' => $this->success,
            'error' => $this->error,
            'warning' => $this->warning,
        ]);
    }
}
