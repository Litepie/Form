<?php

namespace Litepie\Form\Fields;

/**
 * Tel Field
 */
class TelField extends TextField
{
    protected ?string $format = null;
    protected ?string $country = null;
    protected bool $countrySelector = false;
    protected bool $validateFormat = false;
    protected array $preferredCountries = [];
    protected array $onlyCountries = [];
    protected array $excludeCountries = [];
    protected bool $autoFormat = false;
    protected bool $separateDialCode = false;

    protected function getFieldType(): string
    {
        return 'tel';
    }

    public function country(string $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function countrySelector(bool $show = true): self
    {
        $this->countrySelector = $show;
        return $this;
    }

    public function validateFormat(bool $validate = true): self
    {
        $this->validateFormat = $validate;
        return $this;
    }

    public function preferredCountries(array $countries): self
    {
        $this->preferredCountries = $countries;
        return $this;
    }

    public function onlyCountries(array $countries): self
    {
        $this->onlyCountries = $countries;
        return $this;
    }

    public function excludeCountries(array $countries): self
    {
        $this->excludeCountries = $countries;
        return $this;
    }

    public function autoFormat(bool $format = true): self
    {
        $this->autoFormat = $format;
        return $this;
    }

    public function separateDialCode(bool $separate = true): self
    {
        $this->separateDialCode = $separate;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'format' => $this->format,
            'country' => $this->country,
            'countrySelector' => $this->countrySelector,
            'validateFormat' => $this->validateFormat,
            'preferredCountries' => $this->preferredCountries,
            'onlyCountries' => $this->onlyCountries,
            'excludeCountries' => $this->excludeCountries,
            'autoFormat' => $this->autoFormat,
            'separateDialCode' => $this->separateDialCode,
        ]);
    }
}
