<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Textarea Field
 */
class TextareaField extends Field
{
    protected function getFieldType(): string
    {
        return 'textarea';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<textarea name="%s" id="%s" %s>%s</textarea>',
            htmlspecialchars($this->name),
            $this->getId(),
            $attributes,
            htmlspecialchars($this->value ?? '')
        );
    }
}

/**
 * Select Field
 */
class SelectField extends Field
{
    protected function getFieldType(): string
    {
        return 'select';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        $optionsHtml = '';
        
        foreach ($this->options as $value => $text) {
            $selected = $this->value == $value ? ' selected' : '';
            $optionsHtml .= sprintf(
                '<option value="%s"%s>%s</option>',
                htmlspecialchars($value),
                $selected,
                htmlspecialchars($text)
            );
        }
        
        return sprintf(
            '<select name="%s" id="%s" %s>%s</select>',
            htmlspecialchars($this->name),
            $this->getId(),
            $attributes,
            $optionsHtml
        );
    }
}

/**
 * File Field
 */
class FileField extends Field
{
    protected function getFieldType(): string
    {
        return 'file';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="file" name="%s" id="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            $attributes
        );
    }
}

/**
 * Submit Button Field
 */
class SubmitField extends Field
{
    protected function getFieldType(): string
    {
        return 'submit';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="submit" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? 'Submit'),
            $attributes
        );
    }
}
