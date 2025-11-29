<?php

namespace Litepie\Form;

use Illuminate\Container\Container;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Form Builder
 * 
 * Main class for building forms with fluent interface
 */
class FormBuilder
{
    /**
     * The application instance.
     */
    protected Container $app;

    /**
     * Form attributes.
     */
    protected array $attributes = [];

    /**
     * Form fields.
     */
    protected Collection $fields;

    /**
     * Form action URL.
     */
    protected ?string $action = null;

    /**
     * Form method.
     */
    protected string $method = 'POST';

    /**
     * Whether form has file uploads.
     */
    protected bool $hasFiles = false;

    /**
     * Form theme/framework.
     */
    protected string $framework = 'bootstrap5';

    /**
     * Form validation rules.
     */
    protected array $rules = [];

    /**
     * Form data.
     */
    protected array $data = [];

    /**
     * Whether to include CSRF token.
     */
    protected bool $csrf = true;

    /**
     * Whether form should submit via AJAX.
     */
    protected bool $ajax = false;

    /**
     * Form theme/template.
     */
    protected ?string $theme = null;

    /**
     * Whether form is multi-step.
     */
    protected bool $multiStep = false;

    /**
     * Create a new form builder instance.
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
        $this->fields = new Collection();
        $this->framework = config('form.framework', 'bootstrap5');
    }

    /**
     * Set form action.
     */
    public function action(string $action): self
    {
        $this->action = $action;
        return $this;
    }

    /**
     * Set form method.
     */
    public function method(string $method): self
    {
        $this->method = strtoupper($method);
        if ($this->method !== 'GET' && $this->method !== 'POST') {
            $this->hasFiles = true; // For method spoofing
        }
        return $this;
    }

    /**
     * Enable file uploads.
     */
    public function files(bool $files = true): self
    {
        $this->hasFiles = $files;
        return $this;
    }

    /**
     * Set form framework.
     */
    public function framework(string $framework): self
    {
        $this->framework = $framework;
        return $this;
    }

    /**
     * Add form attribute.
     */
    public function attribute(string $key, mixed $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * Add multiple attributes.
     */
    public function attributes(array $attributes): self
    {
        $this->attributes = array_merge($this->attributes, $attributes);
        return $this;
    }

    /**
     * Add a field to the form.
     */
    public function add(string $name, string $type, array $options = []): self
    {
        $field = $this->app->make('form.field')
            ->create($name, $type, $options);

        $this->fields->put($name, $field);

        // Extract validation rules
        if (isset($options['validation'])) {
            $this->rules[$name] = $options['validation'];
        }

        return $this;
    }

    /**
     * Remove a field from the form.
     */
    public function remove(string $name): self
    {
        $this->fields->forget($name);
        unset($this->rules[$name]);
        return $this;
    }

    /**
     * Get a field by name.
     */
    public function field(string $name): ?Field
    {
        return $this->fields->get($name);
    }

    /**
     * Get all fields.
     */
    public function fields(): Collection
    {
        return $this->fields;
    }

    /**
     * Get validation rules.
     */
    public function rules(): array
    {
        return $this->rules;
    }

    /**
     * Fill form with data.
     */
    public function fill(array $data): self
    {
        foreach ($this->fields as $name => $field) {
            if (Arr::has($data, $name)) {
                $field->value(Arr::get($data, $name));
            }
        }
        return $this;
    }

    /**
     * Render the form.
     */
    public function render(): string
    {
        return $this->app->make('form.renderer')
            ->render($this);
    }

    /**
     * Convert form to array for client-side frameworks (Vue, React, etc.)
     */
    public function toArray(): array
    {
        return [
            'config' => [
                'action' => $this->action,
                'method' => $this->method,
                'enctype' => $this->hasFiles ? 'multipart/form-data' : 'application/x-www-form-urlencoded',
                'csrf' => $this->csrf,
                'ajax' => $this->ajax,
                'theme' => $this->theme,
                'multiStep' => $this->multiStep,
                'attributes' => $this->attributes,
            ],
            'fields' => $this->getFieldsArray(),
            'validation' => [
                'rules' => $this->getValidationRules(),
                'messages' => $this->getValidationMessages(),
            ],
            'data' => $this->data,
            'meta' => [
                'fieldCount' => $this->fields->count(),
                'requiredFields' => $this->getRequiredFields(),
                'hasFileUploads' => $this->hasFileUploads(),
                'steps' => $this->getSteps(),
            ]
        ];
    }

    /**
     * Convert form to JSON for API responses
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Get fields as array with all metadata
     */
    protected function getFieldsArray(): array
    {
        $fieldsArray = [];
        
        foreach ($this->fields as $name => $field) {
            $fieldsArray[$name] = [
                'name' => $field->name(),
                'type' => $field->type(),
                'label' => $field->label(),
                'value' => $field->value(),
                'placeholder' => $field->placeholder(),
                'help' => $field->help(),
                'required' => $field->required(),
                'disabled' => $field->disabled(),
                'readonly' => $field->readonly(),
                'attributes' => $field->attributes(),
                'validation' => $field->rules(),
                'options' => $field->options(),
                'class' => $field->class(),
                'id' => $field->id(),
                'step' => $field->step(),
                'conditional' => [
                    'show_if' => $field->showIf(),
                    'hide_if' => $field->hideIf(),
                ],
                'meta' => $this->getFieldMeta($field)
            ];
        }

        return $fieldsArray;
    }

    /**
     * Get field-specific metadata
     */
    protected function getFieldMeta($field): array
    {
        $meta = [
            'hasErrors' => $field->hasErrors(),
            'errors' => $field->errors(),
        ];

        // Add type-specific metadata
        switch ($field->type()) {
            case 'file':
            case 'image':
                $meta['upload'] = [
                    'accept' => $field->accept(),
                    'maxSize' => $field->maxSize(),
                    'multiple' => $field->multiple(),
                    'uploadUrl' => $field->uploadUrl(),
                ];
                break;

            case 'select':
            case 'radio':
            case 'checkbox':
                $meta['selection'] = [
                    'options' => $field->options(),
                    'multiple' => $field->multiple(),
                    'searchable' => $field->searchable(),
                ];
                break;

            case 'number':
            case 'range':
                $meta['numeric'] = [
                    'min' => $field->min(),
                    'max' => $field->max(),
                    'step' => $field->step(),
                ];
                break;

            case 'date':
            case 'datetime':
            case 'time':
                $meta['temporal'] = [
                    'format' => $field->format(),
                    'min' => $field->min(),
                    'max' => $field->max(),
                ];
                break;

            case 'richtext':
                $meta['editor'] = [
                    'config' => $field->config(),
                    'height' => $field->height(),
                ];
                break;

            case 'map':
                $meta['map'] = [
                    'zoom' => $field->zoom(),
                    'center' => $field->center(),
                    'markers' => $field->markers(),
                ];
                break;
        }

        return $meta;
    }

    /**
     * Get list of required field names
     */
    protected function getRequiredFields(): array
    {
        return $this->fields->filter(function ($field) {
            return $field->required();
        })->keys()->toArray();
    }

    /**
     * Check if form has file upload fields
     */
    protected function hasFileUploads(): bool
    {
        return $this->fields->contains(function ($field) {
            return in_array($field->type(), ['file', 'image', 'gallery']);
        });
    }

    /**
     * Get form steps for multi-step forms
     */
    protected function getSteps(): array
    {
        if (!$this->multiStep) {
            return [];
        }

        $steps = [];
        foreach ($this->fields as $field) {
            $step = $field->step() ?? 1;
            if (!isset($steps[$step])) {
                $steps[$step] = [
                    'number' => $step,
                    'fields' => [],
                    'title' => $field->stepTitle() ?? "Step $step",
                ];
            }
            $steps[$step]['fields'][] = $field->name();
        }

        return array_values($steps);
    }

    /**
     * Get validation rules for all fields
     */
    protected function getValidationRules(): array
    {
        $rules = [];
        foreach ($this->fields as $name => $field) {
            if ($fieldRules = $field->rules()) {
                $rules[$name] = $fieldRules;
            }
        }
        return $rules;
    }

    /**
     * Get validation messages for all fields
     */
    protected function getValidationMessages(): array
    {
        $messages = [];
        foreach ($this->fields as $name => $field) {
            if ($fieldMessages = $field->messages()) {
                foreach ($fieldMessages as $rule => $message) {
                    $messages["$name.$rule"] = $message;
                }
            }
        }
        return $messages;
    }

    /**
     * Convert to string.
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Get form opening tag.
     */
    public function open(): string
    {
        $attributes = $this->buildFormAttributes();
        return '<form ' . $this->buildAttributeString($attributes) . '>';
    }

    /**
     * Get form closing tag.
     */
    public function close(): string
    {
        $html = '';
        
        // Add CSRF token
        if ($this->method !== 'GET') {
            $html .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
        }

        // Add method spoofing
        if (!in_array($this->method, ['GET', 'POST'])) {
            $html .= '<input type="hidden" name="_method" value="' . $this->method . '">';
        }

        $html .= '</form>';
        return $html;
    }

    /**
     * Build form attributes.
     */
    protected function buildFormAttributes(): array
    {
        $attributes = $this->attributes;
        
        if ($this->action) {
            $attributes['action'] = $this->action;
        }

        $attributes['method'] = $this->method === 'GET' ? 'GET' : 'POST';

        if ($this->hasFiles) {
            $attributes['enctype'] = 'multipart/form-data';
        }

        return $attributes;
    }

    /**
     * Build attribute string.
     */
    protected function buildAttributeString(array $attributes): string
    {
        $parts = [];
        foreach ($attributes as $key => $value) {
            if ($value === null || $value === false) {
                continue;
            }
            if ($value === true) {
                $parts[] = $key;
            } else {
                $parts[] = $key . '="' . htmlspecialchars($value) . '"';
            }
        }
        return implode(' ', $parts);
    }
}
