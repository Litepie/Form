<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Code Editor Field
 * 
 * For code input with syntax highlighting
 */
class CodeField extends Field
{
    protected string $language = 'javascript';
    protected string $theme = 'default';
    protected bool $lineNumbers = true;
    protected int $height = 300;
    
    /**
     * Get the field type.
     */
    protected function getFieldType(): string
    {
        return 'code';
    }

    /**
     * Set the programming language.
     */
    public function language(string $language): self
    {
        $this->language = $language;
        return $this;
    }

    /**
     * Get the language.
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * Set the editor theme.
     */
    public function theme(string $theme): self
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * Get the theme.
     */
    public function getTheme(): string
    {
        return $this->theme;
    }

    /**
     * Set line numbers visibility.
     */
    public function lineNumbers(bool $show = true): self
    {
        $this->lineNumbers = $show;
        return $this;
    }

    /**
     * Get line numbers setting.
     */
    public function hasLineNumbers(): bool
    {
        return $this->lineNumbers;
    }

    /**
     * Set editor height.
     */
    public function height(int $height): self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * Get code editor height.
     */
    public function getCodeHeight(): int
    {
        return $this->height;
    }

    /**
     * Render the field.
     */
    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<textarea name="%s" id="%s" class="code-editor" data-language="%s" data-theme="%s" data-line-numbers="%s" style="height: %dpx" %s>%s</textarea>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->language),
            htmlspecialchars($this->theme),
            $this->lineNumbers ? 'true' : 'false',
            $this->height,
            $attributes,
            htmlspecialchars($this->value ?? '')
        );
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'language' => $this->language,
            'theme' => $this->theme,
            'lineNumbers' => $this->lineNumbers,
            'height' => $this->height,
        ]);
    }
}
