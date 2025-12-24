<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Rich Text Editor Field
 */
class RichTextField extends Field
{
    protected string $editor = 'tinymce';
    protected array $config = [];
    protected int $height = 300;
    protected array $toolbar = [];
    protected bool $menubar = true;
    protected array $plugins = [];
    protected bool $uploadImages = false;
    protected ?string $imageUploadUrl = null;
    protected ?int $maxImageSize = null;
    protected array $formats = [];
    protected bool $readonly = false;
    protected bool $autoResize = false;
    protected ?int $minHeight = null;
    protected ?int $maxHeight = null;

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

    public function toolbar(array $toolbar): self
    {
        $this->toolbar = $toolbar;
        return $this;
    }

    public function menubar(bool $show = true): self
    {
        $this->menubar = $show;
        return $this;
    }

    public function plugins(array $plugins): self
    {
        $this->plugins = $plugins;
        return $this;
    }

    public function uploadImages(bool $upload = true): self
    {
        $this->uploadImages = $upload;
        return $this;
    }

    public function imageUploadUrl(string $url): self
    {
        $this->imageUploadUrl = $url;
        return $this;
    }

    public function maxImageSize(int $size): self
    {
        $this->maxImageSize = $size;
        return $this;
    }

    public function formats(array $formats): self
    {
        $this->formats = $formats;
        return $this;
    }

    public function readonly(bool $readonly = true): self
    {
        $this->readonly = $readonly;
        return $this;
    }

    public function autoResize(bool $resize = true): self
    {
        $this->autoResize = $resize;
        return $this;
    }

    public function minHeight(int $height): self
    {
        $this->minHeight = $height;
        return $this;
    }

    public function maxHeight(int $height): self
    {
        $this->maxHeight = $height;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'editor' => $this->editor,
            'config' => $this->config,
            'height' => $this->height,
            'toolbar' => $this->toolbar,
            'menubar' => $this->menubar,
            'plugins' => $this->plugins,
            'uploadImages' => $this->uploadImages,
            'imageUploadUrl' => $this->imageUploadUrl,
            'maxImageSize' => $this->maxImageSize,
            'formats' => $this->formats,
            'readonly' => $this->readonly,
            'autoResize' => $this->autoResize,
            'minHeight' => $this->minHeight,
            'maxHeight' => $this->maxHeight,
        ]);
    }
}
