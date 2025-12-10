<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

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

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        if ($this->multiple) {
            $attributes .= ' multiple';
        }
        
        if ($this->accept) {
            $attributes .= ' accept="' . htmlspecialchars($this->accept) . '"';
        }

        $html = '<div class="file-upload-wrapper">';
        
        if ($this->label) {
            $html .= '<label for="' . $this->getId() . '" class="form-label">' . htmlspecialchars($this->label);
            if ($this->required) {
                $html .= ' <span class="text-danger">*</span>';
            }
            $html .= '</label>';
        }

        $html .= '<div class="file-upload-area" data-max-size="' . $this->maxSize . '" data-max-files="' . $this->maxFiles . '">';
        $html .= '<div class="file-upload-content">';
        $html .= '<i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>';
        $html .= '<h5>Drag & drop files here</h5>';
        $html .= '<p class="text-muted">or click to browse</p>';
        $html .= '<small class="text-muted">Max size: ' . $this->maxSize . 'MB per file</small>';
        $html .= '</div>';
        
        $html .= '<input type="file" name="' . htmlspecialchars($this->name) . '" ';
        $html .= 'id="' . $this->getId() . '" class="file-input" ' . $attributes . ' style="display: none;">';
        
        $html .= '</div>';
        
        if ($this->preview) {
            $html .= '<div class="file-preview-container mt-3"></div>';
        }
        
        if ($this->help) {
            $html .= '<div class="form-text">' . htmlspecialchars($this->help) . '</div>';
        }
        
        $html .= '</div>';

        return $html;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'multiple' => $this->multiple,
            'accept' => $this->accept,
            'maxSize' => $this->maxSize,
            'maxFiles' => $this->maxFiles,
            'preview' => $this->preview,
            'uploadUrl' => $this->uploadUrl,
        ]);
    }
}
