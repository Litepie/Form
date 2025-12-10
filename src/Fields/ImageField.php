<?php

namespace Litepie\Form\Fields;

/**
 * Image Upload Field with Cropping
 */
class ImageField extends FileField
{
    protected bool $crop = false;
    protected string $aspectRatio = '16:9';
    protected int $minWidth = 100;
    protected int $minHeight = 100;
    protected array $thumbnails = [];

    protected function getFieldType(): string
    {
        return 'image';
    }

    public function crop(bool $crop = true): self
    {
        $this->crop = $crop;
        return $this;
    }

    public function aspectRatio(string $ratio): self
    {
        $this->aspectRatio = $ratio;
        return $this;
    }

    public function minDimensions(int $width, int $height): self
    {
        $this->minWidth = $width;
        $this->minHeight = $height;
        return $this;
    }

    public function thumbnails(array $sizes): self
    {
        $this->thumbnails = $sizes;
        return $this;
    }

    public function render(): string
    {
        $this->accept = 'image/*';
        
        $html = parent::render();
        
        if ($this->crop) {
            $html .= '<div class="image-cropper mt-3" style="display: none;">';
            $html .= '<div class="cropper-container">';
            $html .= '<img class="cropper-image" style="max-width: 100%;">';
            $html .= '</div>';
            $html .= '<div class="cropper-actions mt-3">';
            $html .= '<button type="button" class="btn btn-primary crop-save">Save Crop</button>';
            $html .= '<button type="button" class="btn btn-secondary crop-cancel">Cancel</button>';
            $html .= '</div>';
            $html .= '</div>';
        }

        return $html;
    }
}
