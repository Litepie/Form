<?php

namespace Litepie\Form\Fields;

/**
 * Number Field
 */
class NumberField extends TextField
{
    protected function getFieldType(): string
    {
        return 'number';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="number" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? ''),
            $attributes
        );
    }
}

/**
 * Tel Field
 */
class TelField extends TextField
{
    protected function getFieldType(): string
    {
        return 'tel';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="tel" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? ''),
            $attributes
        );
    }
}

/**
 * URL Field
 */
class UrlField extends TextField
{
    protected function getFieldType(): string
    {
        return 'url';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="url" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? ''),
            $attributes
        );
    }
}

/**
 * Search Field
 */
class SearchField extends TextField
{
    protected function getFieldType(): string
    {
        return 'search';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="search" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? ''),
            $attributes
        );
    }
}

/**
 * Hidden Field
 */
class HiddenField extends TextField
{
    protected function getFieldType(): string
    {
        return 'hidden';
    }

    public function render(): string
    {
        return sprintf(
            '<input type="hidden" name="%s" id="%s" value="%s">',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? '')
        );
    }
}

/**
 * Date Field
 */
class DateField extends TextField
{
    protected function getFieldType(): string
    {
        return 'date';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="date" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? ''),
            $attributes
        );
    }
}

/**
 * Submit Button Field
 */
class SubmitField extends TextField
{
    protected function getFieldType(): string
    {
        return 'submit';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        $value = $this->getAttribute('value', 'Submit');
        
        return sprintf(
            '<input type="submit" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($value),
            $attributes
        );
    }
}

/**
 * Button Field
 */
class ButtonField extends TextField
{
    protected function getFieldType(): string
    {
        return 'button';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        $text = $this->getAttribute('text', $this->getAttribute('value', 'Button'));
        
        return sprintf(
            '<button type="button" name="%s" id="%s" %s>%s</button>',
            htmlspecialchars($this->name),
            $this->getId(),
            $attributes,
            htmlspecialchars($text)
        );
    }
}

/**
 * Reset Button Field
 */
class ResetField extends TextField
{
    protected function getFieldType(): string
    {
        return 'reset';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        $value = $this->getAttribute('value', 'Reset');
        
        return sprintf(
            '<input type="reset" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($value),
            $attributes
        );
    }
}
