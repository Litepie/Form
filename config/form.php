<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Form Framework
    |--------------------------------------------------------------------------
    |
    | This option controls the default UI framework used for rendering forms.
    | Supported frameworks: "bootstrap5", "bootstrap4", "tailwind", "bulma"
    |
    */
    'framework' => env('FORM_FRAMEWORK', 'bootstrap5'),

    /*
    |--------------------------------------------------------------------------
    | Form Configuration
    |--------------------------------------------------------------------------
    |
    | General form configuration options
    |
    */
    'form' => [
        'automatic_label' => true,
        'label_suffix' => ':',
        'required_text' => '<span class="text-danger">*</span>',
        'error_messages' => true,
        'show_errors_inline' => true,
        'capitalize_translations' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Field Configuration
    |--------------------------------------------------------------------------
    |
    | Default configuration for form fields
    |
    */
    'fields' => [
        'default_wrapper_class' => 'form-group mb-3',
        'default_label_class' => 'form-label',
        'default_input_class' => 'form-control',
        'default_error_class' => 'invalid-feedback',
        'default_help_class' => 'form-text text-muted',
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for file upload fields
    |
    */
    'uploads' => [
        'disk' => env('FORM_UPLOAD_DISK', 'public'),
        'path' => 'uploads',
        'max_size' => '10MB',
        'allowed_mimes' => [
            'images' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
            'documents' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'],
            'archives' => ['zip', 'rar', '7z', 'tar', 'gz'],
        ],
        'image_processing' => [
            'driver' => 'gd', // or 'imagick'
            'quality' => 90,
            'thumbnails' => [
                'small' => [150, 150],
                'medium' => [300, 300],
                'large' => [800, 600],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation Configuration
    |--------------------------------------------------------------------------
    |
    | Default validation rules and messages
    |
    */
    'validation' => [
        'realtime' => true,
        'debounce' => 300, // milliseconds
        'rules' => [
            'text' => 'string|max:255',
            'email' => 'email|max:255',
            'number' => 'numeric',
            'file' => 'file|max:10240', // 10MB in KB
            'image' => 'image|max:5120', // 5MB in KB
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | JavaScript and CSS Assets
    |--------------------------------------------------------------------------
    |
    | Asset configuration for form functionality
    |
    */
    'assets' => [
        'load_default_css' => true,
        'load_default_js' => true,
        'css_framework_cdn' => [
            'bootstrap5' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
            'bootstrap4' => 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css',
            'tailwind' => 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css',
        ],
        'js_framework_cdn' => [
            'bootstrap5' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
            'bootstrap4' => 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js',
        ],
        'dependencies' => [
            'jquery' => 'https://code.jquery.com/jquery-3.7.0.min.js',
            'dropzone' => 'https://unpkg.com/dropzone@5/dist/min/dropzone.min.js',
            'cropper' => 'https://unpkg.com/cropperjs@1.5.12/dist/cropper.min.js',
            'tinymce' => 'https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Advanced Features
    |--------------------------------------------------------------------------
    |
    | Configuration for advanced form features
    |
    */
    'features' => [
        'conditional_logic' => true,
        'field_dependencies' => true,
        'ajax_submission' => true,
        'auto_save' => false,
        'multi_step_forms' => true,
        'field_groups' => true,
        'repeatable_fields' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | Security settings for forms
    |
    */
    'security' => [
        'csrf_protection' => true,
        'xss_protection' => true,
        'rate_limiting' => true,
        'honeypot_protection' => false,
        'encrypt_sensitive_fields' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Localization
    |--------------------------------------------------------------------------
    |
    | Localization settings
    |
    */
    'localization' => [
        'auto_translate_labels' => true,
        'fallback_locale' => 'en',
        'supported_locales' => ['en', 'es', 'fr', 'de', 'it', 'pt', 'ru', 'zh', 'ja'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Configuration
    |--------------------------------------------------------------------------
    |
    | Performance optimization settings
    |
    */
    'performance' => [
        'cache_forms' => true,
        'cache_duration' => 3600, // seconds
        'lazy_load_assets' => true,
        'minify_output' => env('APP_ENV') === 'production',
        'compress_responses' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Caching settings for forms and form containers
    |
    */
    'cache' => [
        'enabled' => env('FORM_CACHE_ENABLED', true),
        'ttl' => env('FORM_CACHE_TTL', 3600), // seconds (1 hour)
        'driver' => env('FORM_CACHE_DRIVER', null), // null uses default cache driver
        'prefix' => env('FORM_CACHE_PREFIX', 'form_cache'),
        'tags' => [
            'forms',
            'containers',
        ],
        'auto_clear_on_update' => true,
        'cache_single_forms' => true,
        'cache_container_renders' => true,
        'cache_form_arrays' => true,
        'cache_visible_forms' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Field Types
    |--------------------------------------------------------------------------
    |
    | Register custom field types here
    |
    */
    'custom_fields' => [
        // 'custom_type' => App\Forms\Fields\CustomField::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme Configuration
    |--------------------------------------------------------------------------
    |
    | Theme-specific settings for different UI frameworks
    |
    */
    'themes' => [
        'bootstrap5' => [
            'form_class' => 'needs-validation',
            'field_wrapper' => 'div',
            'field_wrapper_class' => 'mb-3',
            'label_class' => 'form-label',
            'input_class' => 'form-control',
            'select_class' => 'form-select',
            'textarea_class' => 'form-control',
            'checkbox_class' => 'form-check-input',
            'radio_class' => 'form-check-input',
            'error_class' => 'invalid-feedback',
            'help_class' => 'form-text text-muted',
            'required_class' => 'required',
        ],
        'bootstrap4' => [
            'form_class' => 'needs-validation',
            'field_wrapper' => 'div',
            'field_wrapper_class' => 'form-group',
            'label_class' => 'form-label',
            'input_class' => 'form-control',
            'select_class' => 'form-control',
            'textarea_class' => 'form-control',
            'checkbox_class' => 'form-check-input',
            'radio_class' => 'form-check-input',
            'error_class' => 'invalid-feedback',
            'help_class' => 'form-text text-muted',
            'required_class' => 'required',
        ],
        'tailwind' => [
            'form_class' => 'space-y-6',
            'field_wrapper' => 'div',
            'field_wrapper_class' => 'space-y-1',
            'label_class' => 'block text-sm font-medium text-gray-700',
            'input_class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
            'select_class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
            'textarea_class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
            'checkbox_class' => 'h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded',
            'radio_class' => 'h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300',
            'error_class' => 'mt-2 text-sm text-red-600',
            'help_class' => 'mt-2 text-sm text-gray-500',
            'required_class' => 'required',
        ],
    ],
];
