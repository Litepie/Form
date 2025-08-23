<?php

namespace Litepie\Form;

use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;

/**
 * Form Container
 * 
 * Manages multiple forms within a single container
 * Can be extended for specific use cases
 */
class FormContainer
{
    /**
     * The application instance.
     */
    protected Container $app;

    /**
     * Collection of forms in the container.
     */
    protected Collection $forms;

    /**
     * Container configuration.
     */
    protected array $config = [];

    /**
     * Container attributes.
     */
    protected array $attributes = [];

    /**
     * Container ID.
     */
    protected string $id;

    /**
     * Container name/title.
     */
    protected ?string $name = null;

    /**
     * Container description.
     */
    protected ?string $description = null;

    /**
     * Cache configuration.
     */
    protected array $cacheConfig = [
        'enabled' => true,
        'ttl' => 3600, // 1 hour default
        'driver' => null, // Use default cache driver
        'prefix' => 'form_container',
        'tags' => [],
    ];

    /**
     * Cache store instance.
     */
    protected $cacheStore = null;

    /**
     * Whether forms should be rendered as tabs.
     */
    protected bool $tabbed = false;

    /**
     * Whether forms should be rendered as accordion.
     */
    protected bool $accordion = false;

    /**
     * Active form key (for tabbed/accordion display).
     */
    protected ?string $activeForm = null;

    /**
     * Container theme/framework.
     */
    protected string $framework = 'bootstrap5';

    /**
     * Validation mode for the container.
     */
    protected string $validationMode = 'individual'; // 'individual', 'combined', 'sequential'

    /**
     * Create a new form container instance.
     */
    public function __construct(Container $app, string $id = null)
    {
        $this->app = $app;
        $this->forms = new Collection();
        $this->id = $id ?? 'form_container_' . Str::random(8);
        $this->framework = config('form.framework', 'bootstrap5');
        $this->initializeCache();
    }

    /**
     * Initialize cache configuration.
     */
    protected function initializeCache(): void
    {
        $this->cacheConfig = array_merge($this->cacheConfig, config('form.cache', []));
        
        if ($this->cacheConfig['enabled'] && $this->cacheConfig['driver']) {
            $this->cacheStore = Cache::store($this->cacheConfig['driver']);
        } else {
            $this->cacheStore = Cache::getFacadeRoot();
        }
    }

    /**
     * Set container name/title.
     */
    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set container description.
     */
    public function description(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Set container as tabbed interface.
     */
    public function tabbed(bool $tabbed = true): self
    {
        $this->tabbed = $tabbed;
        if ($tabbed) {
            $this->accordion = false;
        }
        return $this;
    }

    /**
     * Set container as accordion interface.
     */
    public function accordion(bool $accordion = true): self
    {
        $this->accordion = $accordion;
        if ($accordion) {
            $this->tabbed = false;
        }
        return $this;
    }

    /**
     * Set active form for tabbed/accordion display.
     */
    public function activeForm(string $formKey): self
    {
        $this->activeForm = $formKey;
        return $this;
    }

    /**
     * Set framework for all forms in container.
     */
    public function framework(string $framework): self
    {
        $this->framework = $framework;
        
        // Apply to existing forms
        $this->forms->each(function ($form) use ($framework) {
            $form->framework($framework);
        });
        
        return $this;
    }

    /**
     * Set validation mode for the container.
     */
    public function validationMode(string $mode): self
    {
        if (!in_array($mode, ['individual', 'combined', 'sequential'])) {
            throw new InvalidArgumentException("Invalid validation mode: {$mode}");
        }
        
        $this->validationMode = $mode;
        return $this;
    }

    /**
     * Configure caching for the container.
     */
    public function cache(array $config = []): self
    {
        $this->cacheConfig = array_merge($this->cacheConfig, $config);
        $this->initializeCache();
        return $this;
    }

    /**
     * Enable caching.
     */
    public function enableCache(int $ttl = null): self
    {
        $this->cacheConfig['enabled'] = true;
        if ($ttl !== null) {
            $this->cacheConfig['ttl'] = $ttl;
        }
        return $this;
    }

    /**
     * Disable caching.
     */
    public function disableCache(): self
    {
        $this->cacheConfig['enabled'] = false;
        return $this;
    }

    /**
     * Set cache TTL (time to live).
     */
    public function cacheTtl(int $ttl): self
    {
        $this->cacheConfig['ttl'] = $ttl;
        return $this;
    }

    /**
     * Set cache tags.
     */
    public function cacheTags(array $tags): self
    {
        $this->cacheConfig['tags'] = $tags;
        return $this;
    }

    /**
     * Get cache key for a specific operation.
     */
    protected function getCacheKey(string $operation, array $params = []): string
    {
        $baseKey = $this->cacheConfig['prefix'] . ':' . $this->id . ':' . $operation;
        
        if (!empty($params)) {
            $baseKey .= ':' . md5(serialize($params));
        }
        
        return $baseKey;
    }

    /**
     * Clear cache for this container.
     */
    public function clearCache(): self
    {
        if (!$this->cacheConfig['enabled']) {
            return $this;
        }

        $pattern = $this->cacheConfig['prefix'] . ':' . $this->id . ':*';
        
        if (!empty($this->cacheConfig['tags'])) {
            $this->cacheStore->tags($this->cacheConfig['tags'])->flush();
        } else {
            // Clear specific keys (requires cache driver support)
            $keys = [
                $this->getCacheKey('render'),
                $this->getCacheKey('render_tabbed'),
                $this->getCacheKey('render_accordion'),
                $this->getCacheKey('render_stacked'),
                $this->getCacheKey('to_array'),
                $this->getCacheKey('get_visible_forms'),
            ];
            
            foreach ($keys as $key) {
                $this->cacheStore->forget($key);
            }
        }
        
        return $this;
    }

    /**
     * Add container attribute.
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
     * Add a form to the container.
     */
    public function addForm(string $key, FormBuilder $form, array $options = []): self
    {
        // Apply container framework to form
        $form->framework($this->framework);
        
        $this->forms->put($key, [
            'form' => $form,
            'title' => $options['title'] ?? Str::title(str_replace('_', ' ', $key)),
            'description' => $options['description'] ?? null,
            'icon' => $options['icon'] ?? null,
            'badge' => $options['badge'] ?? null,
            'visible' => $options['visible'] ?? true,
            'collapsible' => $options['collapsible'] ?? false,
            'collapsed' => $options['collapsed'] ?? false,
            'order' => $options['order'] ?? $this->forms->count(),
            'class' => $options['class'] ?? '',
            'conditions' => $options['conditions'] ?? [],
        ]);

        // Auto-clear cache when forms are modified
        if ($this->cacheConfig['auto_clear_on_update'] ?? true) {
            $this->clearCache();
        }

        return $this;
    }

    /**
     * Create and add a new form to the container.
     */
    public function createForm(string $key, array $options = []): FormBuilder
    {
        $form = $this->app->make('form.builder');
        $this->addForm($key, $form, $options);
        return $form;
    }

    /**
     * Remove a form from the container.
     */
    public function removeForm(string $key): self
    {
        $this->forms->forget($key);
        
        // Reset active form if removed
        if ($this->activeForm === $key) {
            $this->activeForm = $this->forms->keys()->first();
        }
        
        // Auto-clear cache when forms are modified
        if ($this->cacheConfig['auto_clear_on_update'] ?? true) {
            $this->clearCache();
        }
        
        return $this;
    }

    /**
     * Get a form by key.
     */
    public function getForm(string $key): ?FormBuilder
    {
        $formData = $this->forms->get($key);
        return $formData ? $formData['form'] : null;
    }

    /**
     * Get all forms.
     */
    public function getForms(): Collection
    {
        return $this->forms->map(function ($data) {
            return $data['form'];
        });
    }

    /**
     * Get forms with metadata.
     */
    public function getFormsWithMeta(): Collection
    {
        return $this->forms;
    }

    /**
     * Check if container has a specific form.
     */
    public function hasForm(string $key): bool
    {
        return $this->forms->has($key);
    }

    /**
     * Get form keys.
     */
    public function getFormKeys(): array
    {
        return $this->forms->keys()->toArray();
    }

    /**
     * Get visible forms only.
     */
    public function getVisibleForms(): Collection
    {
        if (!$this->cacheConfig['enabled']) {
            return $this->forms->filter(function ($data) {
                return $data['visible'] === true;
            });
        }

        $cacheKey = $this->getCacheKey('get_visible_forms', [
            'forms_count' => $this->forms->count(),
            'visibility_hash' => md5(serialize($this->forms->map(function ($data) {
                return $data['visible'];
            })->toArray())),
        ]);

        $cache = !empty($this->cacheConfig['tags']) 
            ? $this->cacheStore->tags($this->cacheConfig['tags'])
            : $this->cacheStore;

        return $cache->remember($cacheKey, $this->cacheConfig['ttl'], function () {
            return $this->forms->filter(function ($data) {
                return $data['visible'] === true;
            });
        });
    }

    /**
     * Return only a specific form from the container.
     */
    public function getSingleForm(string $key): ?FormBuilder
    {
        $formData = $this->forms->get($key);
        return $formData ? $formData['form'] : null;
    }

    /**
     * Render only a specific form from the container.
     */
    public function renderSingleForm(string $key): string
    {
        $form = $this->getSingleForm($key);
        
        if (!$form) {
            throw new InvalidArgumentException("Form '{$key}' not found in container.");
        }

        if (!$this->cacheConfig['enabled']) {
            return $form->render();
        }

        $cacheKey = $this->getCacheKey('render_single', [
            'form_key' => $key,
            'framework' => $this->framework,
            'form_hash' => md5(serialize($form->toArray())),
        ]);

        $cache = !empty($this->cacheConfig['tags']) 
            ? $this->cacheStore->tags($this->cacheConfig['tags'])
            : $this->cacheStore;

        return $cache->remember($cacheKey, $this->cacheConfig['ttl'], function () use ($form) {
            return $form->render();
        });
    }

    /**
     * Get single form with its metadata.
     */
    public function getSingleFormWithMeta(string $key): ?array
    {
        return $this->forms->get($key);
    }

    /**
     * Convert single form to array for client-side frameworks.
     */
    public function getSingleFormArray(string $key): array
    {
        $formData = $this->forms->get($key);
        
        if (!$formData) {
            throw new InvalidArgumentException("Form '{$key}' not found in container.");
        }

        $form = $formData['form'];
        
        return array_merge(
            $form->toArray(),
            [
                'container' => [
                    'id' => $this->id,
                    'name' => $this->name,
                    'formKey' => $key,
                    'framework' => $this->framework,
                ],
                'meta' => array_merge($form->toArray()['meta'] ?? [], [
                    'title' => $formData['title'],
                    'description' => $formData['description'],
                    'icon' => $formData['icon'],
                    'badge' => $formData['badge'],
                    'visible' => $formData['visible'],
                    'class' => $formData['class'],
                    'conditions' => $formData['conditions'],
                ])
            ]
        );
    }

    /**
     * Filter container to show only specific forms.
     */
    public function filterForms(array $formKeys): self
    {
        $filtered = new Collection();
        
        foreach ($formKeys as $key) {
            if ($this->forms->has($key)) {
                $filtered->put($key, $this->forms->get($key));
            }
        }
        
        $this->forms = $filtered;
        
        // Update active form if needed
        if ($this->activeForm && !in_array($this->activeForm, $formKeys)) {
            $this->activeForm = $formKeys[0] ?? null;
        }
        
        return $this;
    }

    /**
     * Create a new container with only specific forms.
     */
    public function extractForms(array $formKeys, string $newId = null): FormContainer
    {
        $newContainer = new static($this->app, $newId);
        $newContainer->framework($this->framework)
                     ->name($this->name)
                     ->description($this->description)
                     ->tabbed($this->tabbed)
                     ->accordion($this->accordion)
                     ->validationMode($this->validationMode);

        foreach ($formKeys as $key) {
            if ($this->forms->has($key)) {
                $formData = $this->forms->get($key);
                $newContainer->forms->put($key, $formData);
            }
        }

        // Set active form
        if ($this->activeForm && in_array($this->activeForm, $formKeys)) {
            $newContainer->activeForm($this->activeForm);
        } elseif (!empty($formKeys)) {
            $newContainer->activeForm($formKeys[0]);
        }

        return $newContainer;
    }

    /**
     * Clone container with conditional form visibility.
     */
    public function conditionalRender(callable $condition): FormContainer
    {
        $newContainer = clone $this;
        $newContainer->forms = new Collection();

        foreach ($this->forms as $key => $formData) {
            if ($condition($key, $formData)) {
                $newContainer->forms->put($key, $formData);
            }
        }

        return $newContainer;
    }

    /**
     * Reorder forms.
     */
    public function reorderForms(array $order): self
    {
        $reordered = new Collection();
        
        foreach ($order as $key) {
            if ($this->forms->has($key)) {
                $reordered->put($key, $this->forms->get($key));
            }
        }
        
        // Add any remaining forms not in the order array
        $this->forms->each(function ($data, $key) use ($reordered, $order) {
            if (!in_array($key, $order)) {
                $reordered->put($key, $data);
            }
        });
        
        $this->forms = $reordered;
        return $this;
    }

    /**
     * Populate all forms with data.
     */
    public function populate(array $data): self
    {
        foreach ($this->forms as $key => $formData) {
            $form = $formData['form'];
            
            // Check if data has a specific key for this form
            if (isset($data[$key]) && is_array($data[$key])) {
                $form->fill($data[$key]);
            } else {
                // Use global data for all forms
                $form->fill($data);
            }
        }
        
        return $this;
    }

    /**
     * Validate all forms based on validation mode.
     */
    public function validate(array $data): array
    {
        $results = [];
        
        switch ($this->validationMode) {
            case 'individual':
                foreach ($this->forms as $key => $formData) {
                    $form = $formData['form'];
                    $formData = $data[$key] ?? $data;
                    $results[$key] = $form->validate($formData);
                }
                break;
                
            case 'combined':
                $allValid = true;
                $allErrors = [];
                
                foreach ($this->forms as $key => $formData) {
                    $form = $formData['form'];
                    $formData = $data[$key] ?? $data;
                    $isValid = $form->validate($formData);
                    
                    if (!$isValid) {
                        $allValid = false;
                        $allErrors[$key] = $form->getErrors();
                    }
                    
                    $results[$key] = $isValid;
                }
                
                $results['_combined'] = [
                    'valid' => $allValid,
                    'errors' => $allErrors
                ];
                break;
                
            case 'sequential':
                foreach ($this->forms as $key => $formData) {
                    $form = $formData['form'];
                    $formData = $data[$key] ?? $data;
                    $isValid = $form->validate($formData);
                    $results[$key] = $isValid;
                    
                    // Stop validation if current form fails
                    if (!$isValid) {
                        break;
                    }
                }
                break;
        }
        
        return $results;
    }

    /**
     * Get validation rules for all forms.
     */
    public function getAllValidationRules(): array
    {
        $rules = [];
        
        foreach ($this->forms as $key => $formData) {
            $form = $formData['form'];
            $formRules = $form->rules();
            
            // Prefix rules with form key to avoid conflicts
            foreach ($formRules as $field => $rule) {
                $rules["{$key}.{$field}"] = $rule;
            }
        }
        
        return $rules;
    }

    /**
     * Convert all forms to array for client-side frameworks.
     */
    public function toArray(): array
    {
        if (!$this->cacheConfig['enabled']) {
            return $this->buildArrayRepresentation();
        }

        $cacheKey = $this->getCacheKey('to_array', [
            'forms_count' => $this->forms->count(),
            'forms_hash' => md5(serialize($this->forms->keys()->toArray())),
            'container_config' => md5(serialize([
                'tabbed' => $this->tabbed,
                'accordion' => $this->accordion,
                'framework' => $this->framework,
                'validation_mode' => $this->validationMode,
            ])),
        ]);

        $cache = !empty($this->cacheConfig['tags']) 
            ? $this->cacheStore->tags($this->cacheConfig['tags'])
            : $this->cacheStore;

        return $cache->remember($cacheKey, $this->cacheConfig['ttl'], function () {
            return $this->buildArrayRepresentation();
        });
    }

    /**
     * Build the array representation of the container.
     */
    protected function buildArrayRepresentation(): array
    {
        $formsArray = [];
        
        foreach ($this->forms as $key => $formData) {
            $form = $formData['form'];
            $formsArray[$key] = array_merge(
                $form->toArray(),
                [
                    'meta' => array_merge($form->toArray()['meta'] ?? [], [
                        'title' => $formData['title'],
                        'description' => $formData['description'],
                        'icon' => $formData['icon'],
                        'badge' => $formData['badge'],
                        'visible' => $formData['visible'],
                        'collapsible' => $formData['collapsible'],
                        'collapsed' => $formData['collapsed'],
                        'order' => $formData['order'],
                        'class' => $formData['class'],
                        'conditions' => $formData['conditions'],
                    ])
                ]
            );
        }
        
        return [
            'container' => [
                'id' => $this->id,
                'name' => $this->name,
                'description' => $this->description,
                'framework' => $this->framework,
                'tabbed' => $this->tabbed,
                'accordion' => $this->accordion,
                'activeForm' => $this->activeForm,
                'validationMode' => $this->validationMode,
                'attributes' => $this->attributes,
                'formCount' => $this->forms->count(),
            ],
            'forms' => $formsArray,
        ];
    }

    /**
     * Convert to JSON.
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Render the form container.
     */
    public function render(): string
    {
        if (!$this->cacheConfig['enabled']) {
            return $this->app->make('form.renderer')
                ->renderContainer($this);
        }

        $cacheKey = $this->getCacheKey('render', [
            'tabbed' => $this->tabbed,
            'accordion' => $this->accordion,
            'active_form' => $this->activeForm,
            'framework' => $this->framework,
            'forms_count' => $this->forms->count(),
            'forms_hash' => md5(serialize($this->forms->keys()->toArray())),
        ]);

        $cache = !empty($this->cacheConfig['tags']) 
            ? $this->cacheStore->tags($this->cacheConfig['tags'])
            : $this->cacheStore;

        return $cache->remember($cacheKey, $this->cacheConfig['ttl'], function () {
            return $this->app->make('form.renderer')
                ->renderContainer($this);
        });
    }

    /**
     * Get container ID.
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get container name.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Get container description.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Check if container is tabbed.
     */
    public function isTabbed(): bool
    {
        return $this->tabbed;
    }

    /**
     * Check if container is accordion.
     */
    public function isAccordion(): bool
    {
        return $this->accordion;
    }

    /**
     * Get active form key.
     */
    public function getActiveForm(): ?string
    {
        return $this->activeForm;
    }

    /**
     * Get framework.
     */
    public function getFramework(): string
    {
        return $this->framework;
    }

    /**
     * Get validation mode.
     */
    public function getValidationMode(): string
    {
        return $this->validationMode;
    }

    /**
     * Get container attributes.
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Convert to string.
     */
    public function __toString(): string
    {
        return $this->render();
    }
}
