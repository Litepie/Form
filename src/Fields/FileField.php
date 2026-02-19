<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;
use Litepie\Layout\Components\FormComponent;

/**
 * Advanced File Upload Field with Drag & Drop
 */
class FileField extends Field
{
    protected bool $multiple = false;
    protected string $accept = '';
    protected int $maxSize = 10; // MB
    protected int $maxFiles = 5;
    protected bool $preview = true;
    protected string $uploadUrl = '';
    protected bool $useCropper = false;
    protected mixed $editForm = null;

    protected function getFieldType(): string
    {
        return 'file';
    }

    public function multiple(bool $multiple = true): self
    {
        $this->multiple = $multiple;
        return $this;
    }

    public function accept(string $accept): self
    {
        $this->accept = $accept;
        return $this;
    }

    public function maxSize(int $maxSize): self
    {
        $this->maxSize = $maxSize;
        return $this;
    }

    public function maxFiles(int $maxFiles): self
    {
        $this->maxFiles = $maxFiles;
        return $this;
    }

    public function uploadUrl(string $url): self
    {
        $this->uploadUrl = $url;
        return $this;
    }

    public function preview(bool $preview = true): self
    {
        $this->preview = $preview;
        return $this;
    }

    public function useCropper(bool $useCropper = true): self
    {
        $this->useCropper = $useCropper;
        return $this;
    }

    public function editForm(FormComponent $editForm): self
    {
        $this->editForm = $editForm instanceof FormComponent
            ? $editForm->toArray()
            : (is_array($editForm) ? $editForm : (method_exists($editForm, 'toArray') ? $editForm->toArray() : $editForm));
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'multiple'   => $this->multiple,
            'accept'     => $this->accept,
            'maxSize'    => $this->maxSize,
            'maxFiles'   => $this->maxFiles,
            'preview'    => $this->preview,
            'uploadUrl'  => $this->uploadUrl,
            'useCropper' => $this->useCropper,
            'editForm'   => $this->editForm,
        ]);
    }
}
