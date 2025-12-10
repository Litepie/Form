<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Markdown Editor Field
 * 
 * For markdown content with live preview
 */
class MarkdownField extends Field
{
    protected bool $preview = true;
    protected bool $toolbar = true;
    protected int $height = 400;
    
    /**
     * Get the field type.
     */
    protected function getFieldType(): string
    {
        return 'markdown';
    }

    /**
     * Enable/disable preview pane.
     */
    public function preview(bool $show = true): self
    {
        $this->preview = $show;
        return $this;
    }

    /**
     * Get preview setting.
     */
    public function hasPreview(): bool
    {
        return $this->preview;
    }

    /**
     * Enable/disable toolbar.
     */
    public function toolbar(bool $show = true): self
    {
        $this->toolbar = $show;
        return $this;
    }

    /**
     * Get toolbar setting.
     */
    public function hasToolbar(): bool
    {
        return $this->toolbar;
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
     * Get markdown editor height.
     */
    public function getMarkdownHeight(): int
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
            '<textarea name="%s" id="%s" class="markdown-editor" data-preview="%s" data-toolbar="%s" style="height: %dpx" %s>%s</textarea>',
            htmlspecialchars($this->name),
            $this->getId(),
            $this->preview ? 'true' : 'false',
            $this->toolbar ? 'true' : 'false',
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
            'preview' => $this->preview,
            'toolbar' => $this->toolbar,
            'height' => $this->height,
        ]);
    }
}
