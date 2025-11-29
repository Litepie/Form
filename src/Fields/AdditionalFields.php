<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Color field for color selection
 */
class ColorField extends Field
{
    protected function getFieldType(): string
    {
        return 'color';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="color" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? '#000000'),
            $attributes
        );
    }
}

/**
 * Range/Slider field for numeric ranges
 */
class RangeField extends Field
{
    protected function getFieldType(): string
    {
        return 'range';
    }

    public function render(): string
    {
        $min = $this->attributes['min'] ?? 0;
        $max = $this->attributes['max'] ?? 100;
        $step = $this->attributes['step'] ?? 1;
        
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="range" name="%s" id="%s" value="%s" min="%s" max="%s" step="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? $min),
            htmlspecialchars($min),
            htmlspecialchars($max),
            htmlspecialchars($step),
            $attributes
        );
    }
}

/**
 * Radio button group field
 */
class RadioField extends Field
{
    protected function getFieldType(): string
    {
        return 'radio';
    }

    public function render(): string
    {
        $html = '';
        foreach ($this->options as $optionValue => $optionText) {
            $checked = $this->value == $optionValue ? ' checked' : '';
            $id = $this->getId() . '_' . $optionValue;
            
            $html .= sprintf(
                '<div class="form-check"><input type="radio" name="%s" id="%s" value="%s"%s class="form-check-input"><label for="%s" class="form-check-label">%s</label></div>',
                htmlspecialchars($this->name),
                $id,
                htmlspecialchars($optionValue),
                $checked,
                $id,
                htmlspecialchars($optionText)
            );
        }
        
        return $html;
    }
}

/**
 * Checkbox group field
 */
class CheckboxField extends Field
{
    protected function getFieldType(): string
    {
        return 'checkbox';
    }

    public function render(): string
    {
        if (empty($this->options)) {
            // Single checkbox
            $checked = $this->value ? ' checked' : '';
            return sprintf(
                '<input type="checkbox" name="%s" id="%s" value="1"%s class="form-check-input">',
                htmlspecialchars($this->name),
                $this->getId(),
                $checked
            );
        }
        
        // Checkbox group
        $html = '';
        $values = is_array($this->value) ? $this->value : [$this->value];
        
        foreach ($this->options as $optionValue => $optionText) {
            $checked = in_array($optionValue, $values) ? ' checked' : '';
            $id = $this->getId() . '_' . $optionValue;
            
            $html .= sprintf(
                '<div class="form-check"><input type="checkbox" name="%s[]" id="%s" value="%s"%s class="form-check-input"><label for="%s" class="form-check-label">%s</label></div>',
                htmlspecialchars($this->name),
                $id,
                htmlspecialchars($optionValue),
                $checked,
                $id,
                htmlspecialchars($optionText)
            );
        }
        
        return $html;
    }
}

/**
 * Tags input field
 */
class TagsField extends Field
{
    protected function getFieldType(): string
    {
        return 'text';
    }

    public function render(): string
    {
        $tags = is_array($this->value) ? implode(',', $this->value) : $this->value;
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="text" name="%s" id="%s" value="%s" %s data-role="tagsinput">',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($tags ?? ''),
            $attributes
        );
    }
}

/**
 * Time picker field
 */
class TimeField extends Field
{
    protected function getFieldType(): string
    {
        return 'time';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="time" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? ''),
            $attributes
        );
    }
}

/**
 * Week picker field
 */
class WeekField extends Field
{
    protected function getFieldType(): string
    {
        return 'week';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="week" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? ''),
            $attributes
        );
    }
}

/**
 * Month picker field
 */
class MonthField extends Field
{
    protected function getFieldType(): string
    {
        return 'month';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="month" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? ''),
            $attributes
        );
    }
}

/**
 * Datetime-local field
 */
class DateTimeField extends Field
{
    protected function getFieldType(): string
    {
        return 'datetime-local';
    }

    public function render(): string
    {
        $value = $this->value;
        if ($value && !str_contains($value, 'T')) {
            $value = date('Y-m-d\TH:i', strtotime($value));
        }
        
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="datetime-local" name="%s" id="%s" value="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($value ?? ''),
            $attributes
        );
    }
}

/**
 * Gallery/Multiple Images field
 */
class GalleryField extends Field
{
    protected function getFieldType(): string
    {
        return 'file';
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="file" name="%s[]" id="%s" accept="image/*" multiple %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            $attributes
        );
    }
}

/**
 * Rating/Stars field
 */
class RatingField extends Field
{
    protected function getFieldType(): string
    {
        return 'number';
    }

    public function render(): string
    {
        $max = $this->attributes['max'] ?? 5;
        $step = $this->attributes['allowHalf'] ?? false ? '0.5' : '1';
        
        $attributes = $this->buildAttributes();
        
        return sprintf(
            '<input type="number" name="%s" id="%s" value="%s" min="0" max="%s" step="%s" %s>',
            htmlspecialchars($this->name),
            $this->getId(),
            htmlspecialchars($this->value ?? '0'),
            htmlspecialchars($max),
            $step,
            $attributes
        );
    }
}
