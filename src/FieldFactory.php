<?php

namespace Litepie\Form;

use Illuminate\Container\Container;

/**
 * Field Factory
 * 
 * Creates field instances based on type
 */
class FieldFactory
{
    /**
     * The application instance.
     */
    protected Container $app;

    /**
     * Registered field types.
     */
    protected array $fieldTypes = [
        // Basic input fields
        'text' => Fields\TextField::class,
        'email' => Fields\EmailField::class,
        'password' => Fields\PasswordField::class,
        'number' => Fields\NumberField::class,
        'tel' => Fields\TelField::class,
        'url' => Fields\UrlField::class,
        'search' => Fields\SearchField::class,
        'hidden' => Fields\HiddenField::class,
        
        // Text areas and rich content
        'textarea' => Fields\TextareaField::class,
        'richtext' => Fields\RichTextField::class,
        'markdown' => Fields\MarkdownField::class,
        'code' => Fields\CodeField::class,
        'json' => Fields\JsonField::class,
        
        // Selection fields
        'select' => Fields\SelectField::class,
        'radio' => Fields\RadioField::class,
        'checkbox' => Fields\CheckboxField::class,
        'checkbox_group' => Fields\CheckboxGroupField::class,
        'tags' => Fields\TagsField::class,
        'toggle' => Fields\ToggleField::class,
        'autocomplete' => Fields\AutocompleteField::class,
        
        // File upload fields
        'file' => Fields\FileField::class,
        'image' => Fields\ImageField::class,
        'gallery' => Fields\GalleryField::class,
        
        // Date and time fields
        'date' => Fields\DateField::class,
        'time' => Fields\TimeField::class,
        'datetime' => Fields\DateTimeField::class,
        'datetime-local' => Fields\DateTimeLocalField::class,
        'week' => Fields\WeekField::class,
        'month' => Fields\MonthField::class,
        'daterange' => Fields\DateRangeField::class,
        
        // Specialized numeric fields
        'currency' => Fields\CurrencyField::class,
        'percentage' => Fields\PercentageField::class,
        
        // Visual and interactive fields
        'color' => Fields\ColorField::class,
        'range' => Fields\RangeField::class,
        'rating' => Fields\RatingField::class,
        'map' => Fields\MapField::class,
        'icon' => Fields\IconField::class,
        
        // Complex/Dynamic fields
        'repeater' => Fields\RepeaterField::class,
        'keyvalue' => Fields\KeyValueField::class,
        
        // Layout fields
        'divider' => Fields\DividerField::class,
        'html' => Fields\HtmlField::class,
        
        // Form control fields
        'submit' => Fields\SubmitField::class,
        'button' => Fields\ButtonField::class,
        'reset' => Fields\ResetField::class,
    ];

    /**
     * Create a new field factory instance.
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Create a field instance.
     */
    public function create(string $name, string $type, array $options = []): Field
    {
        $fieldClass = $this->fieldTypes[$type] ?? Fields\TextField::class;
        
        return new $fieldClass($name, $options);
    }

    /**
     * Register a custom field type.
     */
    public function extend(string $type, string $class): void
    {
        $this->fieldTypes[$type] = $class;
    }

    /**
     * Get all registered field types.
     */
    public function getFieldTypes(): array
    {
        return $this->fieldTypes;
    }
}
