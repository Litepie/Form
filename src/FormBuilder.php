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
     * The user for visibility checks.
     */
    protected ?object $user = null;

    /**
     * Cache storage for form outputs.
     */
    protected array $cache = [];

    /**
     * Cache TTL in seconds (default: 1 hour).
     */
    protected int $cacheTtl = 3600;

    /**
     * Whether caching is enabled.
     */
    protected bool $cacheEnabled = false;

    /**
     * Row counter for auto-generating row IDs.
     */
    protected int $rowCounter = 0;

    /**
     * Group counter for auto-generating group IDs.
     */
    protected int $groupCounter = 0;

    /**
     * Section counter for auto-generating section IDs.
     */
    protected int $sectionCounter = 0;

    /**
     * Current active group.
     */
    protected ?string $currentGroup = null;

    /**
     * Current active section.
     */
    protected ?string $currentSection = null;

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
     * Set the user for visibility checks.
     */
    public function forUser(?object $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get the current user.
     */
    public function getUser(): ?object
    {
        return $this->user;
    }

    /**
     * Set default field width for this form.
     */
    public function defaultWidth(int $width): self
    {
        Field::setDefaultWidth($width);
        return $this;
    }

    /**
     * Enable caching with optional TTL.
     */
    public function cache(int $ttl = 3600): self
    {
        $this->cacheEnabled = true;
        $this->cacheTtl = $ttl;
        return $this;
    }

    /**
     * Disable caching.
     */
    public function withoutCache(): self
    {
        $this->cacheEnabled = false;
        $this->cache = [];
        return $this;
    }

    /**
     * Clear all cached outputs.
     */
    public function clearCache(): self
    {
        $this->cache = [];
        return $this;
    }

    /**
     * Get or set cached value.
     */
    protected function cached(string $key, callable $callback)
    {
        if (!$this->cacheEnabled) {
            return $callback();
        }

        // Check if cache exists and is not expired
        if (isset($this->cache[$key])) {
            $cacheData = $this->cache[$key];
            if (time() < $cacheData['expires_at']) {
                return $cacheData['value'];
            }
            // Cache expired, remove it
            unset($this->cache[$key]);
        }

        // Generate new value and cache it
        $value = $callback();
        $this->cache[$key] = [
            'value' => $value,
            'expires_at' => time() + $this->cacheTtl,
            'created_at' => time()
        ];

        return $value;
    }

    /**
     * Generate cache key based on operation and user.
     */
    protected function getCacheKey(string $operation, ?object $user = null): string
    {
        $userKey = $user ? spl_object_hash($user) : 'no_user';
        return $operation . '_' . $userKey;
    }

    /**
     * Set form theme.
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
     * Add multiple fields in a row.
     */
    public function row(array $fields, ?string $rowId = null): self
    {
        // Auto-generate row ID if not provided
        if ($rowId === null) {
            $this->rowCounter++;
            $rowId = 'row' . $this->rowCounter;
        }

        // Add each field with the same row identifier
        foreach ($fields as $field) {
            if ($field instanceof Field) {
                // Set the row identifier
                $field->row($rowId);
                
                // Set current group and section if active
                if ($this->currentGroup) {
                    $field->group($this->currentGroup);
                }
                if ($this->currentSection) {
                    $field->section($this->currentSection);
                }
                
                // Add field to the form
                $this->fields->put($field->getName(), $field);
                
                // Extract validation rules if any
                if ($rules = $field->getRules()) {
                    $this->rules[$field->getName()] = $rules;
                }
            }
        }

        return $this;
    }

    /**
     * Start a new group with optional title and description.
     */
    public function group(string $groupId, ?string $title = null, ?string $description = null): self
    {
        $this->currentGroup = $groupId;
        $this->currentSection = null; // Reset section when starting new group
        
        return $this;
    }

    /**
     * Start a new section within current group.
     */
    public function section(string $sectionId, ?string $title = null, ?string $description = null): self
    {
        $this->currentSection = $sectionId;
        
        return $this;
    }

    /**
     * End the current group.
     */
    public function endGroup(): self
    {
        $this->currentGroup = null;
        $this->currentSection = null;
        
        return $this;
    }

    /**
     * End the current section.
     */
    public function endSection(): self
    {
        $this->currentSection = null;
        
        return $this;
    }

    /**
     * Add a divider with optional label.
     */
    public function divider(?string $label = null, ?string $group = null, ?string $section = null): self
    {
        // Create a pseudo-field for the divider
        $dividerName = 'divider_' . (count($this->fields) + 1);
        
        $dividerField = new class($dividerName, $label, $group ?? $this->currentGroup, $section ?? $this->currentSection) extends Field {
            protected string $dividerLabel;
            protected ?string $dividerGroup;
            protected ?string $dividerSection;
            
            public function __construct(string $name, ?string $label, ?string $group, ?string $section)
            {
                $this->name = $name;
                $this->type = 'divider';
                $this->dividerLabel = $label ?? '';
                $this->dividerGroup = $group;
                $this->dividerSection = $section;
                
                if ($group) $this->group = $group;
                if ($section) $this->section = $section;
            }
            
            protected function getFieldType(): string
            {
                return 'divider';
            }
            
            public function render(): string
            {
                $label = $this->dividerLabel ? '<span>' . htmlspecialchars($this->dividerLabel) . '</span>' : '';
                return '<hr class="form-divider">' . $label;
            }
            
            public function getDividerLabel(): string
            {
                return $this->dividerLabel;
            }
        };
        
        $this->fields->put($dividerName, $dividerField);
        
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
     * Get visible fields only.
     */
    public function visibleFields(?object $user = null): Collection
    {
        return $this->fields->filter(function ($field) use ($user) {
            return $field->isVisible($user);
        });
    }

    /**
     * Get form data.
     */
    public function getData(): array
    {
        return $this->data;
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
    public function render(?object $user = null): string
    {
        // Use provided user or fall back to stored user
        $user = $user ?? $this->user;
        
        return $this->cached($this->getCacheKey('render', $user), function() use ($user) {
            // Store user for renderer access
            if ($user !== null) {
                $this->data['_user'] = $user;
            }
            
            return $this->app->make('form.renderer')
                ->render($this);
        });
    }

    /**
     * Convert form to array for client-side frameworks (Vue, React, etc.)
     */
    public function toArray(?object $user = null): array
    {
        // Use provided user or fall back to stored user
        $user = $user ?? $this->user;
        
        return $this->cached($this->getCacheKey('toArray', $user), function() use ($user) {
            // Get visible fields if user is provided
            $fields = $user ? $this->visibleFields($user) : $this->fields;
            
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
                'fields' => $this->getFieldsArray($user),
                'validation' => [
                    'rules' => $this->getValidationRules($user),
                    'messages' => $this->getValidationMessages($user),
                ],
                'data' => $this->data,
                'meta' => [
                    'fieldCount' => $fields->count(),
                    'requiredFields' => $this->getRequiredFields($user),
                    'hasFileUploads' => $this->hasFileUploads(),
                    'steps' => $this->getSteps($user),
                ]
            ];
        });
    }

    /**
     * Convert form to JSON for API responses
     */
    public function toJson(?object $user = null, int $options = 0): string
    {
        // Use provided user or fall back to stored user
        $user = $user ?? $this->user;
        
        $cacheKey = $this->getCacheKey('toJson_' . $options, $user);
        
        return $this->cached($cacheKey, function() use ($user, $options) {
            return json_encode($this->toArray($user), $options);
        });
    }

    /**
     * Get fields as array with all metadata
     */
    protected function getFieldsArray(?object $user = null): array
    {
        $fieldsArray = [];
        
        // Get visible fields if user is provided
        $fields = $user ? $this->visibleFields($user) : $this->fields;
        
        foreach ($fields as $name => $field) {
            // Double-check visibility
            if ($user && !$field->isVisible($user)) {
                continue;
            }
            $fieldsArray[$name] = [
                'name' => $field->getName(),
                'type' => $field->getType(),
                'label' => $field->getLabel(),
                'value' => $field->getValue(),
                'placeholder' => $field->getPlaceholder(),
                'help' => $field->getHelp(),
                'required' => $field->isRequired(),
                'disabled' => $field->getDisabled(),
                'readonly' => $field->getReadonly(),
                'attributes' => $field->getAttributes(),
                'validation' => $field->getRules(),
                'options' => $field->getOptions(),
                'class' => $field->getClass(),
                'id' => $field->getId(),
                'step' => $field->getStep(),
                'width' => $field->getWidth(),
                'totalColumns' => $field->getTotalColumns(),
                'row' => $field->getRow(),
                'group' => $field->getGroup(),
                'section' => $field->getSection(),
                'conditional' => [
                    'show_if' => $field->getShowIf(),
                    'hide_if' => $field->getHideIf(),
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
            'errors' => $field->getErrors(),
        ];

        // Add type-specific metadata
        switch ($field->getType()) {
            case 'file':
            case 'image':
                $meta['upload'] = [
                    'accept' => $field->getAccept(),
                    'maxSize' => $field->getMaxSize(),
                    'multiple' => $field->getMultiple(),
                    'uploadUrl' => $field->getUploadUrl(),
                ];
                break;

            case 'select':
            case 'radio':
            case 'checkbox':
                $meta['selection'] = [
                    'options' => $field->getOptions(),
                    'multiple' => $field->getMultiple(),
                    'searchable' => $field->getSearchable(),
                ];
                break;

            case 'number':
            case 'range':
                $meta['numeric'] = [
                    'min' => $field->getMin(),
                    'max' => $field->getMax(),
                    'step' => $field->getStep(),
                ];
                break;

            case 'date':
            case 'datetime':
            case 'time':
                $meta['temporal'] = [
                    'format' => $field->getFormat(),
                    'min' => $field->getMin(),
                    'max' => $field->getMax(),
                ];
                break;

            case 'richtext':
                $meta['editor'] = [
                    'config' => $field->getConfig(),
                    'height' => $field->getHeight(),
                ];
                break;

            case 'map':
                $meta['map'] = [
                    'zoom' => $field->getZoom(),
                    'center' => $field->getCenter(),
                    'markers' => $field->getMarkers(),
                ];
                break;
        }

        return $meta;
    }

    /**
     * Get list of required field names
     */
    protected function getRequiredFields(?object $user = null): array
    {
        $fields = $user ? $this->visibleFields($user) : $this->fields;
        
        return $fields->filter(function ($field) {
            return $field->isRequired();
        })->keys()->toArray();
    }

    /**
     * Check if form has file upload fields
     */
    protected function hasFileUploads(): bool
    {
        return $this->fields->contains(function ($field) {
            return in_array($field->getType(), ['file', 'image', 'gallery']);
        });
    }

    /**
     * Get form steps for multi-step forms
     */
    protected function getSteps(?object $user = null): array
    {
        if (!$this->multiStep) {
            return [];
        }

        $fields = $user ? $this->visibleFields($user) : $this->fields;
        
        $steps = [];
        foreach ($fields as $field) {
            $step = $field->getStep() ?? 1;
            if (!isset($steps[$step])) {
                $steps[$step] = [
                    'number' => $step,
                    'fields' => [],
                    'title' => $field->getStepTitle() ?? "Step $step",
                ];
            }
            $steps[$step]['fields'][] = $field->getName();
        }

        return array_values($steps);
    }

    /**
     * Get validation rules for all fields
     */
    protected function getValidationRules(?object $user = null): array
    {
        $fields = $user ? $this->visibleFields($user) : $this->fields;
        
        $rules = [];
        foreach ($fields as $name => $field) {
            if ($fieldRules = $field->getRules()) {
                $rules[$name] = $fieldRules;
            }
        }
        return $rules;
    }

    /**
     * Get validation messages for all fields
     */
    protected function getValidationMessages(?object $user = null): array
    {
        $fields = $user ? $this->visibleFields($user) : $this->fields;
        
        $messages = [];
        foreach ($fields as $name => $field) {
            if ($fieldMessages = $field->getMessages()) {
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
