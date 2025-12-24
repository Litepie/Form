<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Textarea Field
 */
class TextareaField extends Field
{
    protected int $rows = 4;
    protected ?int $cols = null;
    protected ?int $minLength = null;
    protected ?int $maxLength = null;
    protected bool $showCounter = false;
    protected bool $autoResize = false;
    protected ?int $minHeight = null;
    protected ?int $maxHeight = null;
    protected string $resize = 'vertical';
    protected bool $spellcheck = true;

    protected function getFieldType(): string
    {
        return 'textarea';
    }

    public function rows(int $rows): self
    {
        $this->rows = $rows;
        return $this;
    }

    public function cols(int $cols): self
    {
        $this->cols = $cols;
        return $this;
    }

    public function showCounter(bool $show = true): self
    {
        $this->showCounter = $show;
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

    public function resize(string $resize): self
    {
        $this->resize = $resize;
        return $this;
    }

    public function spellcheck(bool $check = true): self
    {
        $this->spellcheck = $check;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'rows' => $this->rows,
            'cols' => $this->cols,
            'minLength' => $this->minLength,
            'maxLength' => $this->maxLength,
            'showCounter' => $this->showCounter,
            'autoResize' => $this->autoResize,
            'minHeight' => $this->minHeight,
            'maxHeight' => $this->maxHeight,
            'resize' => $this->resize,
            'spellcheck' => $this->spellcheck,
        ]);
    }
}
