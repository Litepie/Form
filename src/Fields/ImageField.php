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
    protected ?int $maxWidth = null;
    protected ?int $maxHeight = null;
    protected int $quality = 90;
    protected ?string $format = null;
    protected bool $rotate = false;
    protected bool $flip = false;
    protected array $filters = [];
    protected ?array $watermark = null;

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

    public function maxWidth(int $width): self
    {
        $this->maxWidth = $width;
        return $this;
    }

    public function maxHeight(int $height): self
    {
        $this->maxHeight = $height;
        return $this;
    }

    public function quality(int $quality): self
    {
        $this->quality = $quality;
        return $this;
    }

    public function rotate(bool $rotate = true): self
    {
        $this->rotate = $rotate;
        return $this;
    }

    public function flip(bool $flip = true): self
    {
        $this->flip = $flip;
        return $this;
    }

    public function filters(array $filters): self
    {
        $this->filters = $filters;
        return $this;
    }

    public function watermark(array $config): self
    {
        $this->watermark = $config;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'crop' => $this->crop,
            'aspectRatio' => $this->aspectRatio,
            'minWidth' => $this->minWidth,
            'minHeight' => $this->minHeight,
            'thumbnails' => $this->thumbnails,
            'maxWidth' => $this->maxWidth,
            'maxHeight' => $this->maxHeight,
            'quality' => $this->quality,
            'format' => $this->format,
            'rotate' => $this->rotate,
            'flip' => $this->flip,
            'filters' => $this->filters,
            'watermark' => $this->watermark,
        ]);
    }
}
