<?php

namespace Litepie\Form\Fields;

/**
 * Email Field
 */
class EmailField extends TextField
{
    protected bool $multiple = false;
    protected bool $validateDomain = false;
    protected array $allowedDomains = [];
    protected array $blockedDomains = [];
    protected bool $suggestDomains = false;
    protected array $commonDomains = ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com'];

    /**
     * Get the field type.
     */
    protected function getFieldType(): string
    {
        return 'email';
    }

    public function multiple(bool $multiple = true): self
    {
        $this->multiple = $multiple;
        return $this;
    }

    public function validateDomain(bool $validate = true): self
    {
        $this->validateDomain = $validate;
        return $this;
    }

    public function allowedDomains(array $domains): self
    {
        $this->allowedDomains = $domains;
        return $this;
    }

    public function blockedDomains(array $domains): self
    {
        $this->blockedDomains = $domains;
        return $this;
    }

    public function suggestDomains(bool $suggest = true): self
    {
        $this->suggestDomains = $suggest;
        return $this;
    }

    public function commonDomains(array $domains): self
    {
        $this->commonDomains = $domains;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'multiple' => $this->multiple,
            'validateDomain' => $this->validateDomain,
            'allowedDomains' => $this->allowedDomains,
            'blockedDomains' => $this->blockedDomains,
            'suggestDomains' => $this->suggestDomains,
            'commonDomains' => $this->commonDomains,
        ]);
    }
}
