<?php

if (!function_exists('form')) {
    /**
     * Get the form manager instance.
     */
    function form(): \Litepie\Form\FormManager
    {
        return app('form');
    }
}

if (!function_exists('form_create')) {
    /**
     * Create a new form builder instance.
     */
    function form_create(): \Litepie\Form\FormBuilder
    {
        return form()->create();
    }
}

if (!function_exists('form_quick')) {
    /**
     * Create a quick form with array configuration.
     */
    function form_quick(array $fields, array $options = []): \Litepie\Form\FormBuilder
    {
        return form()->quick($fields, $options);
    }
}

if (!function_exists('form_field')) {
    /**
     * Create a standalone field.
     */
    function form_field(string $name, string $type, array $options = []): \Litepie\Form\Field
    {
        return app('form.field')->create($name, $type, $options);
    }
}

if (!function_exists('form_container')) {
    /**
     * Create a new form container instance.
     */
    function form_container(string $id = null): \Litepie\Form\FormContainer
    {
        return form()->container($id);
    }
}

if (!function_exists('form_container_quick')) {
    /**
     * Create a quick form container with multiple forms.
     */
    function form_container_quick(array $forms, array $options = []): \Litepie\Form\FormContainer
    {
        return form()->quickContainer($forms, $options);
    }
}

if (!function_exists('form_container_single')) {
    /**
     * Get a single form from a container.
     */
    function form_container_single(\Litepie\Form\FormContainer $container, string $formKey): ?\Litepie\Form\FormBuilder
    {
        return $container->getSingleForm($formKey);
    }
}

if (!function_exists('form_container_render_single')) {
    /**
     * Render a single form from a container.
     */
    function form_container_render_single(\Litepie\Form\FormContainer $container, string $formKey): string
    {
        return $container->renderSingleForm($formKey);
    }
}

if (!function_exists('form_container_cache')) {
    /**
     * Configure caching for a container.
     */
    function form_container_cache(\Litepie\Form\FormContainer $container, array $config = []): \Litepie\Form\FormContainer
    {
        return $container->cache($config);
    }
}

if (!function_exists('form_container_cache_enable')) {
    /**
     * Enable caching for a container.
     */
    function form_container_cache_enable(\Litepie\Form\FormContainer $container, int $ttl = null): \Litepie\Form\FormContainer
    {
        return $container->enableCache($ttl);
    }
}

if (!function_exists('form_container_cache_disable')) {
    /**
     * Disable caching for a container.
     */
    function form_container_cache_disable(\Litepie\Form\FormContainer $container): \Litepie\Form\FormContainer
    {
        return $container->disableCache();
    }
}

if (!function_exists('form_container_cache_clear')) {
    /**
     * Clear cache for a container.
     */
    function form_container_cache_clear(\Litepie\Form\FormContainer $container): \Litepie\Form\FormContainer
    {
        return $container->clearCache();
    }
}

if (!function_exists('form_csrf')) {
    /**
     * Generate CSRF token field.
     */
    function form_csrf(): string
    {
        return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('form_method')) {
    /**
     * Generate method spoofing field.
     */
    function form_method(string $method): string
    {
        if (in_array(strtoupper($method), ['GET', 'POST'])) {
            return '';
        }
        
        return '<input type="hidden" name="_method" value="' . strtoupper($method) . '">';
    }
}

if (!function_exists('form_errors')) {
    /**
     * Get validation errors for a field.
     */
    function form_errors(string $field, array $errors = null): array
    {
        $errors = $errors ?? session('errors', collect())->toArray();
        
        return $errors[$field] ?? [];
    }
}

if (!function_exists('form_old')) {
    /**
     * Get old input value for a field.
     */
    function form_old(string $field, mixed $default = null): mixed
    {
        return old($field, $default);
    }
}

if (!function_exists('form_config')) {
    /**
     * Get form configuration value.
     */
    function form_config(string $key, mixed $default = null): mixed
    {
        return config("form.{$key}", $default);
    }
}

if (!function_exists('form_theme_config')) {
    /**
     * Get theme-specific configuration.
     */
    function form_theme_config(string $key, ?string $theme = null): mixed
    {
        $theme = $theme ?? config('form.framework', 'bootstrap5');
        
        return config("form.themes.{$theme}.{$key}");
    }
}

if (!function_exists('form_asset_url')) {
    /**
     * Get asset URL for form resources.
     */
    function form_asset_url(string $path): string
    {
        return asset("vendor/form/{$path}");
    }
}

if (!function_exists('form_js_config')) {
    /**
     * Generate JavaScript configuration object.
     */
    function form_js_config(): string
    {
        $config = [
            'csrf_token' => csrf_token(),
            'validation' => [
                'realtime' => config('form.validation.realtime', true),
                'debounce' => config('form.validation.debounce', 300),
            ],
            'uploads' => [
                'max_size' => config('form.uploads.max_size', '10MB'),
                'allowed_mimes' => config('form.uploads.allowed_mimes', []),
            ],
            'ajax_url' => url('/'),
        ];
        
        return 'window.FormConfig = ' . json_encode($config) . ';';
    }
}

if (!function_exists('form_include_assets')) {
    /**
     * Include form assets (CSS and JS).
     */
    function form_include_assets(bool $css = true, bool $js = true): string
    {
        $html = '';
        
        if ($css && config('form.assets.load_default_css', true)) {
            $framework = config('form.framework', 'bootstrap5');
            $cssUrl = config("form.assets.css_framework_cdn.{$framework}");
            
            if ($cssUrl) {
                $html .= '<link rel="stylesheet" href="' . $cssUrl . '">' . PHP_EOL;
            }
            
            $html .= '<link rel="stylesheet" href="' . form_asset_url('css/form.css') . '">' . PHP_EOL;
        }
        
        if ($js && config('form.assets.load_default_js', true)) {
            $framework = config('form.framework', 'bootstrap5');
            $jsUrl = config("form.assets.js_framework_cdn.{$framework}");
            
            if ($jsUrl) {
                $html .= '<script src="' . $jsUrl . '"></script>' . PHP_EOL;
            }
            
            $html .= '<script src="' . form_asset_url('js/form.js') . '"></script>' . PHP_EOL;
            $html .= '<script>' . form_js_config() . '</script>' . PHP_EOL;
        }
        
        return $html;
    }
}

if (!function_exists('form_array')) {
    /**
     * Create a form as array for client-side frameworks (Vue, React, etc.)
     */
    function form_array(array $fields, array $options = []): array
    {
        $form = form_create();
        
        // Apply form options
        if (isset($options['action'])) {
            $form->action($options['action']);
        }
        if (isset($options['method'])) {
            $form->method($options['method']);
        }
        if (isset($options['files'])) {
            $form->files($options['files']);
        }
        if (isset($options['theme'])) {
            $form->theme($options['theme']);
        }
        if (isset($options['ajax'])) {
            $form->ajax($options['ajax']);
        }
        
        // Add fields
        foreach ($fields as $name => $config) {
            if (is_string($config)) {
                $form->add($name, $config);
            } elseif (is_array($config)) {
                $type = $config['type'] ?? 'text';
                unset($config['type']);
                $form->add($name, $type, $config);
            }
        }
        
        return $form->toArray();
    }
}

if (!function_exists('form_json')) {
    /**
     * Create a form as JSON for API responses
     */
    function form_json(array $fields, array $options = []): string
    {
        return json_encode(form_array($fields, $options));
    }
}

if (!function_exists('form_schema')) {
    /**
     * Generate JSON Schema for form validation
     */
    function form_schema(array $fields): array
    {
        $schema = [
            '$schema' => 'https://json-schema.org/draft/2020-12/schema',
            'type' => 'object',
            'properties' => [],
            'required' => []
        ];
        
        foreach ($fields as $name => $config) {
            if (is_string($config)) {
                $config = ['type' => $config];
            }
            
            $fieldSchema = [
                'type' => 'string' // Default type
            ];
            
            // Map field types to JSON Schema types
            switch ($config['type'] ?? 'text') {
                case 'number':
                case 'range':
                    $fieldSchema['type'] = 'number';
                    if (isset($config['min'])) $fieldSchema['minimum'] = $config['min'];
                    if (isset($config['max'])) $fieldSchema['maximum'] = $config['max'];
                    break;
                
                case 'email':
                    $fieldSchema['format'] = 'email';
                    break;
                    
                case 'url':
                    $fieldSchema['format'] = 'uri';
                    break;
                    
                case 'date':
                    $fieldSchema['format'] = 'date';
                    break;
                    
                case 'datetime':
                    $fieldSchema['format'] = 'date-time';
                    break;
                    
                case 'checkbox':
                    if (isset($config['options'])) {
                        $fieldSchema['type'] = 'array';
                        $fieldSchema['items'] = ['type' => 'string'];
                    } else {
                        $fieldSchema['type'] = 'boolean';
                    }
                    break;
                    
                case 'select':
                case 'radio':
                    if (isset($config['options'])) {
                        $fieldSchema['enum'] = array_keys($config['options']);
                    }
                    break;
            }
            
            if (isset($config['label'])) {
                $fieldSchema['title'] = $config['label'];
            }
            
            if (isset($config['help'])) {
                $fieldSchema['description'] = $config['help'];
            }
            
            if (isset($config['maxlength'])) {
                $fieldSchema['maxLength'] = $config['maxlength'];
            }
            
            if (isset($config['minlength'])) {
                $fieldSchema['minLength'] = $config['minlength'];
            }
            
            $schema['properties'][$name] = $fieldSchema;
            
            if ($config['required'] ?? false) {
                $schema['required'][] = $name;
            }
        }
        
        return $schema;
    }
}
