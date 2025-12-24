<?php

namespace Litepie\Form\Fields;

/**
 * URL Field
 */
class UrlField extends TextField
{
    protected bool $validateUrl = true;
    protected array $allowedProtocols = ['http', 'https'];
    protected bool $requireProtocol = false;
    protected bool $autoProtocol = false;
    protected bool $openLink = false;
    protected bool $checkExistence = false;

    protected function getFieldType(): string
    {
        return 'url';
    }

    public function validateUrl(bool $validate = true): self
    {
        $this->validateUrl = $validate;
        return $this;
    }

    public function allowedProtocols(array $protocols): self
    {
        $this->allowedProtocols = $protocols;
        return $this;
    }

    public function requireProtocol(bool $require = true): self
    {
        $this->requireProtocol = $require;
        return $this;
    }

    public function autoProtocol(bool $auto = true): self
    {
        $this->autoProtocol = $auto;
        return $this;
    }

    public function openLink(bool $open = true): self
    {
        $this->openLink = $open;
        return $this;
    }

    public function checkExistence(bool $check = true): self
    {
        $this->checkExistence = $check;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'validateUrl' => $this->validateUrl,
            'allowedProtocols' => $this->allowedProtocols,
            'requireProtocol' => $this->requireProtocol,
            'autoProtocol' => $this->autoProtocol,
            'openLink' => $this->openLink,
            'checkExistence' => $this->checkExistence,
        ]);
    }
}
