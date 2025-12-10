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
