<?php
namespace Litepie\Form;

use Illuminate\Support\Str;
use Litepie\Form\Concerns\HasAttributes;
use Litepie\Form\Concerns\HasDependencies;
use Litepie\Form\Concerns\HasLayout;
use Litepie\Form\Concerns\HasValidation;
use Litepie\Form\Concerns\HasVisibility;

/**
 * Base Field Class
 *
 * Abstract base class for all form fields.
 * Uses traits to organize functionality into cohesive concerns.
 */
abstract class Field
{
    use HasVisibility;
    use HasValidation;
    use HasLayout;
    use HasAttributes;
    use HasDependencies;

    /**
     * Field name.
     */
    protected string $name;

    /**
     * Field type.
     */
    protected string $type;

    /**
     * Field value.
     */
    protected mixed $value = null;

    /**
     * Field label.
     */
    protected ?string $label = null;

    /**
     * Field placeholder.
     */
    protected ?string $placeholder = null;

    /**
     * Field options (for select, radio, checkbox fields).
     */
    protected array $options = [];

    /**
     * Create a field instance using the factory.
     *
     * @param string $type Field type (text, email, select, etc.)
     * @param string $name Field name
     * @param array $options Additional field options
     * @return static
     */
    public static function make(string $type, string $name, array $options = []): Field
    {
        $factory = \Illuminate\Container\Container::getInstance()->make(FieldFactory::class);
        return $factory->create($name, $type, $options);
    }

    /**
     * Create a new field instance.
     */
    public function __construct(string $name, array $options = [])
    {
        $this->name = $name;
        $this->type = $this->getFieldType();

        $this->setOptions($options);
    }

    /**
     * Get the field type.
     */
    abstract protected function getFieldType(): string;

    /**
     * Set field options.
     */
    protected function setOptions(array $options): void
    {
        foreach ($options as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            } elseif ($key === 'class') {
                $this->addClass($value);
            } else {
                $this->attributes[$key] = $value;
            }
        }
    }

    /**
     * Set field value.
     */
    public function value(mixed $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Get field value.
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * Get or set field label.
     */
    public function label(?string $label = null): string | self
    {
        if ($label === null) {
            return $this->label ?: Str::title(str_replace('_', ' ', $this->name));
        }

        $this->label = $label;
        return $this;
    }

    /**
     * Get field label.
     */
    public function getLabel(): ?string
    {
        return $this->label ?: Str::title(str_replace('_', ' ', $this->name));
    }

    /**
     * Get or set field placeholder.
     */
    public function placeholder(?string $placeholder = null): self | string | null
    {
        if ($placeholder === null) {
            return $this->placeholder;
        }

        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * Get field placeholder.
     */
    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    /**
     * Get or set options.
     */
    public function options(?array $options = null): array | self
    {
        if ($options === null) {
            return $this->options;
        }

        $this->options = $options;
        return $this;
    }

    /**
     * Get field options.
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Get field name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get field type.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get field ID.
     */
    public function getId(): string
    {
        return $this->attributes['id'] ?? str_replace(['[', ']'], ['_', ''], $this->name);
    }

    /**
     * Convert field to array.
     */
    public function toArray(): array
    {
        return [
            'name'                 => $this->name,
            'type'                 => $this->type,
            'value'                => $this->value,
            'label'                => $this->getLabel(),
            'placeholder'          => $this->placeholder,
            'attributes'           => $this->attributes,
            'options'              => $this->options,
            'required'             => $this->required,
            'validation'           => $this->validation,
            'help'                 => $this->help,
            'tooltip'              => $this->tooltip,
            'example'              => $this->example,
            'errors'               => $this->errors,
            'visible'              => $this->visible,
            'readonly'             => $this->readonly,
            'disabled'             => $this->disabled,
            'id'                   => $this->getId(),
            'dependsOn'            => $this->dependsOn,
            'isComputed'           => $this->isComputed(),
            'requiredConditions'   => $this->requiredConditions,
            'validationMessages'   => $this->validationMessages,
            'loadingText'          => $this->loadingText,
            'confirmMessage'       => $this->confirmMessage,
            'trackChanges'         => $this->trackChanges,
            'permission'           => $this->permission,
            'roles'                => $this->roles,
            'width'                => $this->width,
            'totalColumns'         => $this->totalColumns,
            'row'                  => $this->row,
            'group'                => $this->group,
            'section'              => $this->section,
            'columns'              => $this->columns,
            'visibilityConditions' => $this->visibilityConditions,
        ];
    }

}
