<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Color field for color selection
 */
class ColorField extends Field
{
    protected $type = 'color';

    protected function renderField()
    {
        $attributes = $this->buildAttributes([
            'type' => 'color',
            'name' => $this->name,
            'id' => $this->getAttribute('id', $this->name),
            'value' => $this->value,
            'class' => $this->buildClasses(),
        ]);

        return view('litepie-form::fields.color', [
            'field' => $this,
            'attributes' => $attributes,
            'palette' => $this->getAttribute('palette', [
                '#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff', '#00ffff',
                '#000000', '#ffffff', '#808080', '#800000', '#008000', '#000080'
            ]),
            'format' => $this->getAttribute('format', 'hex'), // hex, rgb, hsl
            'allowAlpha' => $this->getAttribute('allowAlpha', false),
        ]);
    }

    public function getValidationRules()
    {
        $rules = parent::getValidationRules();
        
        if ($this->getAttribute('format') === 'hex') {
            $rules[] = 'regex:/^#[a-fA-F0-9]{6}$/';
        }
        
        return $rules;
    }
}

/**
 * Range/Slider field for numeric ranges
 */
class RangeField extends Field
{
    protected $type = 'range';

    protected function renderField()
    {
        $min = $this->getAttribute('min', 0);
        $max = $this->getAttribute('max', 100);
        $step = $this->getAttribute('step', 1);
        $value = $this->value ?: $min;

        $attributes = $this->buildAttributes([
            'type' => 'range',
            'name' => $this->name,
            'id' => $this->getAttribute('id', $this->name),
            'value' => $value,
            'min' => $min,
            'max' => $max,
            'step' => $step,
            'class' => $this->buildClasses(),
        ]);

        return view('litepie-form::fields.range', [
            'field' => $this,
            'attributes' => $attributes,
            'showValue' => $this->getAttribute('showValue', true),
            'valuePrefix' => $this->getAttribute('valuePrefix', ''),
            'valueSuffix' => $this->getAttribute('valueSuffix', ''),
            'ticks' => $this->getAttribute('ticks', []),
        ]);
    }

    public function getValidationRules()
    {
        $rules = parent::getValidationRules();
        
        if ($min = $this->getAttribute('min')) {
            $rules[] = "min:$min";
        }
        
        if ($max = $this->getAttribute('max')) {
            $rules[] = "max:$max";
        }
        
        $rules[] = 'numeric';
        
        return $rules;
    }
}

/**
 * Radio button group field
 */
class RadioField extends Field
{
    protected $type = 'radio';

    protected function renderField()
    {
        $options = $this->getAttribute('options', []);
        $inline = $this->getAttribute('inline', false);
        $value = $this->value;

        return view('litepie-form::fields.radio', [
            'field' => $this,
            'options' => $options,
            'value' => $value,
            'inline' => $inline,
            'name' => $this->name,
            'id' => $this->getAttribute('id', $this->name),
            'class' => $this->buildClasses(),
        ]);
    }

    public function getValidationRules()
    {
        $rules = parent::getValidationRules();
        
        if ($options = $this->getAttribute('options')) {
            $validValues = array_keys($options);
            $rules[] = 'in:' . implode(',', $validValues);
        }
        
        return $rules;
    }
}

/**
 * Checkbox group field
 */
class CheckboxField extends Field
{
    protected $type = 'checkbox';

    protected function renderField()
    {
        $options = $this->getAttribute('options', []);
        $inline = $this->getAttribute('inline', false);
        $value = $this->value;
        
        // Handle single checkbox vs checkbox group
        if (empty($options)) {
            return $this->renderSingleCheckbox();
        }

        return view('litepie-form::fields.checkbox', [
            'field' => $this,
            'options' => $options,
            'value' => is_array($value) ? $value : [$value],
            'inline' => $inline,
            'name' => $this->name . '[]',
            'id' => $this->getAttribute('id', $this->name),
            'class' => $this->buildClasses(),
        ]);
    }

    protected function renderSingleCheckbox()
    {
        $attributes = $this->buildAttributes([
            'type' => 'checkbox',
            'name' => $this->name,
            'id' => $this->getAttribute('id', $this->name),
            'value' => $this->getAttribute('value', '1'),
            'class' => $this->buildClasses(),
        ]);

        if ($this->value) {
            $attributes['checked'] = 'checked';
        }

        return view('litepie-form::fields.checkbox-single', [
            'field' => $this,
            'attributes' => $attributes,
            'checkboxLabel' => $this->getAttribute('checkboxLabel', ''),
        ]);
    }

    public function getValidationRules()
    {
        $rules = parent::getValidationRules();
        
        if ($options = $this->getAttribute('options')) {
            $validValues = array_keys($options);
            $rules[] = 'array';
            $rules[] = 'in:' . implode(',', $validValues);
        }
        
        return $rules;
    }
}

/**
 * Tags input field
 */
class TagsField extends Field
{
    protected $type = 'tags';

    protected function renderField()
    {
        $value = $this->value;
        $tags = is_array($value) ? $value : explode(',', $value);
        $suggestions = $this->getAttribute('suggestions', []);
        $maxTags = $this->getAttribute('maxTags', null);
        $allowCustom = $this->getAttribute('allowCustom', true);

        return view('litepie-form::fields.tags', [
            'field' => $this,
            'tags' => array_filter($tags),
            'suggestions' => $suggestions,
            'maxTags' => $maxTags,
            'allowCustom' => $allowCustom,
            'name' => $this->name,
            'id' => $this->getAttribute('id', $this->name),
            'class' => $this->buildClasses(),
            'placeholder' => $this->getAttribute('placeholder', 'Type and press Enter...'),
        ]);
    }

    public function getValidationRules()
    {
        $rules = parent::getValidationRules();
        
        if ($maxTags = $this->getAttribute('maxTags')) {
            $rules[] = "max:$maxTags";
        }
        
        if (!$this->getAttribute('allowCustom', true) && $suggestions = $this->getAttribute('suggestions')) {
            $rules[] = 'in:' . implode(',', $suggestions);
        }
        
        return $rules;
    }
}

/**
 * Time picker field
 */
class TimeField extends Field
{
    protected $type = 'time';

    protected function renderField()
    {
        $attributes = $this->buildAttributes([
            'type' => 'time',
            'name' => $this->name,
            'id' => $this->getAttribute('id', $this->name),
            'value' => $this->value,
            'class' => $this->buildClasses(),
            'step' => $this->getAttribute('step', '60'), // seconds
        ]);

        return view('litepie-form::fields.time', [
            'field' => $this,
            'attributes' => $attributes,
            'format' => $this->getAttribute('format', '24'), // 12 or 24 hour
            'minuteStep' => $this->getAttribute('minuteStep', 1),
            'showSeconds' => $this->getAttribute('showSeconds', false),
        ]);
    }

    public function getValidationRules()
    {
        $rules = parent::getValidationRules();
        $rules[] = 'date_format:H:i' . ($this->getAttribute('showSeconds') ? ':s' : '');
        
        return $rules;
    }
}

/**
 * Week picker field
 */
class WeekField extends Field
{
    protected $type = 'week';

    protected function renderField()
    {
        $attributes = $this->buildAttributes([
            'type' => 'week',
            'name' => $this->name,
            'id' => $this->getAttribute('id', $this->name),
            'value' => $this->value,
            'class' => $this->buildClasses(),
        ]);

        return view('litepie-form::fields.week', [
            'field' => $this,
            'attributes' => $attributes,
        ]);
    }

    public function getValidationRules()
    {
        $rules = parent::getValidationRules();
        $rules[] = 'date_format:Y-\WW';
        
        return $rules;
    }
}

/**
 * Month picker field
 */
class MonthField extends Field
{
    protected $type = 'month';

    protected function renderField()
    {
        $attributes = $this->buildAttributes([
            'type' => 'month',
            'name' => $this->name,
            'id' => $this->getAttribute('id', $this->name),
            'value' => $this->value,
            'class' => $this->buildClasses(),
        ]);

        return view('litepie-form::fields.month', [
            'field' => $this,
            'attributes' => $attributes,
        ]);
    }

    public function getValidationRules()
    {
        $rules = parent::getValidationRules();
        $rules[] = 'date_format:Y-m';
        
        return $rules;
    }
}

/**
 * Datetime-local field
 */
class DateTimeField extends Field
{
    protected $type = 'datetime-local';

    protected function renderField()
    {
        $value = $this->value;
        if ($value && !str_contains($value, 'T')) {
            // Convert from database format to HTML datetime-local format
            $value = date('Y-m-d\TH:i', strtotime($value));
        }

        $attributes = $this->buildAttributes([
            'type' => 'datetime-local',
            'name' => $this->name,
            'id' => $this->getAttribute('id', $this->name),
            'value' => $value,
            'class' => $this->buildClasses(),
        ]);

        return view('litepie-form::fields.datetime', [
            'field' => $this,
            'attributes' => $attributes,
            'timezone' => $this->getAttribute('timezone', 'UTC'),
        ]);
    }

    public function getValidationRules()
    {
        $rules = parent::getValidationRules();
        $rules[] = 'date';
        
        return $rules;
    }
}

/**
 * Gallery/Multiple Images field
 */
class GalleryField extends Field
{
    protected $type = 'gallery';

    protected function renderField()
    {
        $maxFiles = $this->getAttribute('maxFiles', 10);
        $accept = $this->getAttribute('accept', 'image/*');
        $sortable = $this->getAttribute('sortable', true);
        $existingFiles = $this->value ?: [];

        return view('litepie-form::fields.gallery', [
            'field' => $this,
            'maxFiles' => $maxFiles,
            'accept' => $accept,
            'sortable' => $sortable,
            'existingFiles' => $existingFiles,
            'name' => $this->name,
            'id' => $this->getAttribute('id', $this->name),
            'class' => $this->buildClasses(),
            'uploadUrl' => $this->getAttribute('uploadUrl', '/upload'),
            'deleteUrl' => $this->getAttribute('deleteUrl', '/delete'),
        ]);
    }
}

/**
 * Rating/Stars field
 */
class RatingField extends Field
{
    protected $type = 'rating';

    protected function renderField()
    {
        $max = $this->getAttribute('max', 5);
        $allowHalf = $this->getAttribute('allowHalf', false);
        $readonly = $this->getAttribute('readonly', false);
        $value = (float) $this->value;

        return view('litepie-form::fields.rating', [
            'field' => $this,
            'max' => $max,
            'allowHalf' => $allowHalf,
            'readonly' => $readonly,
            'value' => $value,
            'name' => $this->name,
            'id' => $this->getAttribute('id', $this->name),
            'class' => $this->buildClasses(),
            'size' => $this->getAttribute('size', 'medium'), // small, medium, large
        ]);
    }

    public function getValidationRules()
    {
        $rules = parent::getValidationRules();
        $rules[] = 'numeric';
        $rules[] = 'min:0';
        
        if ($max = $this->getAttribute('max', 5)) {
            $rules[] = "max:$max";
        }
        
        return $rules;
    }
}
