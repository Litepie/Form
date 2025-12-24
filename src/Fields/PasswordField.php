<?php

namespace Litepie\Form\Fields;

/**
 * Password Field
 */
class PasswordField extends TextField
{
    protected bool $showToggle = false;
    protected bool $strengthMeter = false;
    protected int $minStrength = 0;
    protected bool $generateButton = false;
    protected ?string $confirmField = null;
    protected array $requirements = [];
    protected bool $requirementsDisplay = false;

    /**
     * Get the field type.
     */
    protected function getFieldType(): string
    {
        return 'password';
    }

    public function showToggle(bool $show = true): self
    {
        $this->showToggle = $show;
        return $this;
    }

    public function strengthMeter(bool $show = true): self
    {
        $this->strengthMeter = $show;
        return $this;
    }

    public function minStrength(int $strength): self
    {
        $this->minStrength = $strength;
        return $this;
    }

    public function generateButton(bool $show = true): self
    {
        $this->generateButton = $show;
        return $this;
    }

    public function confirmField(string $fieldName): self
    {
        $this->confirmField = $fieldName;
        return $this;
    }

    public function requirements(array $requirements): self
    {
        $this->requirements = $requirements;
        return $this;
    }

    public function requirementsDisplay(bool $display = true): self
    {
        $this->requirementsDisplay = $display;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'showToggle' => $this->showToggle,
            'strengthMeter' => $this->strengthMeter,
            'minStrength' => $this->minStrength,
            'generateButton' => $this->generateButton,
            'confirmField' => $this->confirmField,
            'requirements' => $this->requirements,
            'requirementsDisplay' => $this->requirementsDisplay,
        ]);
    }
}
