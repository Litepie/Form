<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * HTML/Static Content Field
 * 
 * Display-only HTML content
 */
class HtmlField extends Field
{
    protected string $content = '';
    protected bool $escapeHtml = false;
    
    /**
     * Get the field type.
     */
    protected function getFieldType(): string
    {
        return 'html';
    }

    /**
     * Set HTML content.
     */
    public function content(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get HTML content.
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Enable/disable HTML escaping.
     */
    public function escapeHtml(bool $escape = true): self
    {
        $this->escapeHtml = $escape;
        return $this;
    }

    /**
     * Get escape HTML setting.
     */
    public function shouldEscapeHtml(): bool
    {
        return $this->escapeHtml;
    }

    /**
     * Render the field.
     */
    public function render(): string
    {
        $content = $this->escapeHtml 
            ? htmlspecialchars($this->content) 
            : $this->content;
            
        return sprintf(
            '<div class="html-field">%s</div>',
            $content
        );
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'content' => $this->content,
            'escapeHtml' => $this->escapeHtml,
        ]);
    }
}
