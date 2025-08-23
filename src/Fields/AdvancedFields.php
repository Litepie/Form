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

/**
 * Date Range Field
 */
class DateRangeField extends Field
{
    protected ?string $startDate = null;
    protected ?string $endDate = null;
    protected string $format = 'Y-m-d';
    protected string $separator = ' to ';

    protected function getFieldType(): string
    {
        return 'daterange';
    }

    public function format(string $format): self
    {
        $this->format = $format;
        return $this;
    }

    public function separator(string $separator): self
    {
        $this->separator = $separator;
        return $this;
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        $html = '<div class="date-range-wrapper">';
        
        if ($this->label) {
            $html .= '<label class="form-label">' . htmlspecialchars($this->label);
            if ($this->required) {
                $html .= ' <span class="text-danger">*</span>';
            }
            $html .= '</label>';
        }

        $html .= '<div class="input-group">';
        $html .= '<input type="date" name="' . htmlspecialchars($this->name) . '[start]" ';
        $html .= 'class="form-control" placeholder="Start Date" ' . $attributes . '>';
        $html .= '<span class="input-group-text">' . htmlspecialchars($this->separator) . '</span>';
        $html .= '<input type="date" name="' . htmlspecialchars($this->name) . '[end]" ';
        $html .= 'class="form-control" placeholder="End Date" ' . $attributes . '>';
        $html .= '</div>';
        
        if ($this->help) {
            $html .= '<div class="form-text">' . htmlspecialchars($this->help) . '</div>';
        }
        
        $html .= '</div>';

        return $html;
    }
}

/**
 * Rich Text Editor Field
 */
class RichTextField extends Field
{
    protected string $editor = 'tinymce';
    protected array $config = [];
    protected int $height = 300;

    protected function getFieldType(): string
    {
        return 'richtext';
    }

    public function editor(string $editor): self
    {
        $this->editor = $editor;
        return $this;
    }

    public function config(array $config): self
    {
        $this->config = $config;
        return $this;
    }

    public function height(int $height): self
    {
        $this->height = $height;
        return $this;
    }

    public function render(): string
    {
        $html = '<div class="rich-text-wrapper">';
        
        if ($this->label) {
            $html .= '<label for="' . $this->getId() . '" class="form-label">' . htmlspecialchars($this->label);
            if ($this->required) {
                $html .= ' <span class="text-danger">*</span>';
            }
            $html .= '</label>';
        }

        $html .= '<textarea name="' . htmlspecialchars($this->name) . '" ';
        $html .= 'id="' . $this->getId() . '" class="form-control rich-text-editor" ';
        $html .= 'style="height: ' . $this->height . 'px;" ';
        $html .= 'data-editor="' . $this->editor . '" ';
        $html .= 'data-config="' . htmlspecialchars(json_encode($this->config)) . '"';
        if ($this->required) {
            $html .= ' required';
        }
        $html .= '>' . htmlspecialchars($this->value ?? '') . '</textarea>';
        
        if ($this->help) {
            $html .= '<div class="form-text">' . htmlspecialchars($this->help) . '</div>';
        }
        
        $html .= '</div>';

        return $html;
    }
}

/**
 * Map Location Picker Field
 */
class MapField extends Field
{
    protected float $latitude = 0.0;
    protected float $longitude = 0.0;
    protected int $zoom = 10;
    protected string $provider = 'google';
    protected string $apiKey = '';

    protected function getFieldType(): string
    {
        return 'map';
    }

    public function coordinates(float $lat, float $lng): self
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
        return $this;
    }

    public function zoom(int $zoom): self
    {
        $this->zoom = $zoom;
        return $this;
    }

    public function provider(string $provider): self
    {
        $this->provider = $provider;
        return $this;
    }

    public function apiKey(string $key): self
    {
        $this->apiKey = $key;
        return $this;
    }

    public function render(): string
    {
        $html = '<div class="map-field-wrapper">';
        
        if ($this->label) {
            $html .= '<label class="form-label">' . htmlspecialchars($this->label);
            if ($this->required) {
                $html .= ' <span class="text-danger">*</span>';
            }
            $html .= '</label>';
        }

        $html .= '<div class="row">';
        $html .= '<div class="col-md-6">';
        $html .= '<input type="number" name="' . htmlspecialchars($this->name) . '[latitude]" ';
        $html .= 'class="form-control latitude-input" placeholder="Latitude" ';
        $html .= 'value="' . $this->latitude . '" step="any">';
        $html .= '</div>';
        $html .= '<div class="col-md-6">';
        $html .= '<input type="number" name="' . htmlspecialchars($this->name) . '[longitude]" ';
        $html .= 'class="form-control longitude-input" placeholder="Longitude" ';
        $html .= 'value="' . $this->longitude . '" step="any">';
        $html .= '</div>';
        $html .= '</div>';

        $html .= '<div class="map-container mt-3" style="height: 400px;" ';
        $html .= 'data-lat="' . $this->latitude . '" ';
        $html .= 'data-lng="' . $this->longitude . '" ';
        $html .= 'data-zoom="' . $this->zoom . '" ';
        $html .= 'data-provider="' . $this->provider . '" ';
        $html .= 'data-api-key="' . $this->apiKey . '">';
        $html .= '</div>';
        
        if ($this->help) {
            $html .= '<div class="form-text">' . htmlspecialchars($this->help) . '</div>';
        }
        
        $html .= '</div>';

        return $html;
    }
}
