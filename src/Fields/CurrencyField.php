<?php

namespace Litepie\Form\Fields;

/**
 * Currency Field
 * 
 * Specialized number field for money with currency symbol
 */
class CurrencyField extends NumberField
{
    protected string $currency = 'USD';
    protected string $locale = 'en_US';
    protected string $symbol = '$';
    protected string $symbolPosition = 'before'; // before or after
    
    /**
     * Get the field type.
     */
    protected function getFieldType(): string
    {
        return 'currency';
    }

    /**
     * Set the currency code.
     */
    public function currency(string $currency): self
    {
        $this->currency = $currency;
        $this->symbol = $this->getCurrencySymbol($currency);
        return $this;
    }

    /**
     * Get the currency code.
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Set the locale.
     */
    public function locale(string $locale): self
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * Get the locale.
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * Set the currency symbol.
     */
    public function symbol(string $symbol): self
    {
        $this->symbol = $symbol;
        return $this;
    }

    /**
     * Get the currency symbol.
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * Set symbol position.
     */
    public function symbolPosition(string $position): self
    {
        $this->symbolPosition = $position;
        return $this;
    }

    /**
     * Get symbol position.
     */
    public function getSymbolPosition(): string
    {
        return $this->symbolPosition;
    }

    /**
     * Get currency symbol from code.
     */
    protected function getCurrencySymbol(string $currency): string
    {
        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
            'CNY' => '¥',
            'INR' => '₹',
            'AUD' => 'A$',
            'CAD' => 'C$',
            'CHF' => 'Fr',
            'SEK' => 'kr',
            'NZD' => 'NZ$',
        ];
        
        return $symbols[$currency] ?? $currency;
    }

    /**
     * Render the field.
     */
    public function render(): string
    {
        $attributes = $this->buildAttributes();
        $symbol = htmlspecialchars($this->symbol);
        
        if ($this->symbolPosition === 'before') {
            return sprintf(
                '<div class="input-group"><span class="input-group-text">%s</span><input type="number" name="%s" id="%s" value="%s" step="0.01" %s></div>',
                $symbol,
                htmlspecialchars($this->name),
                $this->getId(),
                htmlspecialchars($this->value ?? ''),
                $attributes
            );
        } else {
            return sprintf(
                '<div class="input-group"><input type="number" name="%s" id="%s" value="%s" step="0.01" %s><span class="input-group-text">%s</span></div>',
                htmlspecialchars($this->name),
                $this->getId(),
                htmlspecialchars($this->value ?? ''),
                $attributes,
                $symbol
            );
        }
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'currency' => $this->currency,
            'locale' => $this->locale,
            'symbol' => $this->symbol,
            'symbolPosition' => $this->symbolPosition,
        ]);
    }
}
