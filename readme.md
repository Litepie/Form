# Litepie Form Builder

A comprehensive, production-ready Laravel form builder package inspired by the original Litepie/Form but completely rewritten for Laravel 12 with modern design patterns and advanced features.

## 📋 Table of Contents

- [Features](#-features)
- [Installation](#-installation)
- [Quick Start](#-quick-start)
- [Field Types](#-field-types)
- [Advanced Features](#-advanced-features)
- [Form Container](#-form-container---managing-multiple-forms)
- [Client-Side Integration](#-client-side-integration)
- [Validation](#-advanced-validation)
- [File Uploads](#-file-uploads)
- [Performance & Caching](#-performance--caching)
- [Testing](#-testing)
- [API Reference](#-api-reference)
- [Documentation](#-documentation)
- [Contributing](#-contributing)
- [Security](#-security)
- [Support](#-support)

## ✨ Features

- **🚀 Laravel 12 Ready**: Built for Laravel 11+ with full Laravel 12 compatibility
- **🎨 Multiple UI Frameworks*## 🔒 Security

If you discover security vulnerabilities, please review our [Security Policy](SECURITY.md) and send an email to security@litepie.com instead of using the issue tracker.Bootstr## 📚 Documentation

- **[Documentation Index](doc/index.md)** - Complete documentation overview and navigation
- **[Examples](doc/examples.md)** - Comprehensive examples for all field types and features
- **[Container Examples](doc/container-examples.md)** - Multi-form container usage and patterns
- **[Client-Side Examples](doc/client-side-examples.md)** - JavaScript integration and API usage
- **[Caching Guide](doc/caching.md)** - Performance optimization with cachingBootstrap 4, Tailwind CSS, and custom themes
- **📝 30+ Field Types**: Complete field library including advanced types like rich text, maps, file uploads
- **✅ Advanced Validation**: Real-time validation, conditional rules, custom validators
- **🔀 Conditional Logic**: Dynamic field visibility, validation, and multi-step forms
- **📁 File Management**: Drag & drop uploads, image cropping, gallery management, cloud storage
- **📦 Form Container**: Manage multiple forms with tabbed, accordion, or stacked interfaces
- **♿ Accessibility**: WCAG 2.1 AA compliant with full screen reader support
- **⚡ Performance**: Optimized rendering, asset bundling, lazy loading
- **🧪 Well Tested**: Comprehensive test suite with 95%+ code coverage
- **🎯 Developer Friendly**: Fluent API, extensive documentation, helper functions

## 📦 Installation

```bash
composer require litepie/form
```

Publish the configuration and assets:

```bash
php artisan vendor:publish --provider="Litepie\Form\FormServiceProvider"
php artisan form:install
```

Include the CSS and JavaScript assets in your layout:

```blade
<!-- In your layout head -->
{!! form_include_assets(true, false) !!} {{-- CSS only --}}

<!-- Before closing body tag -->
{!! form_include_assets(false, true) !!} {{-- JS only --}}

<!-- Or include both at once -->
{!! form_include_assets() !!}
```

## 🚀 Quick Start

### Basic Contact Form

```php
use Litepie\Form\Facades\Form;

$contactForm = Form::create()
    ->action('/contact')
    ->method('POST')
    ->add('name', 'text', [
        'label' => 'Full Name',
        'required' => true,
        'placeholder' => 'Enter your full name',
        'validation' => 'required|string|max:255'
    ])
    ->add('email', 'email', [
        'label' => 'Email Address',
        'required' => true,
        'validation' => 'required|email'
    ])
    ->add('message', 'textarea', [
        'label' => 'Message',
        'required' => true,
        'rows' => 5,
        'validation' => 'required|string|min:10'
    ])
    ->add('submit', 'submit', [
        'value' => 'Send Message',
        'class' => 'btn btn-primary'
    ]);

// In your Blade template
{!! $contactForm->render() !!}
```

### Registration Form with File Upload

```php
$registrationForm = Form::create()
    ->action('/register')
    ->method('POST')
    ->files(true)
    ->add('avatar', 'image', [
        'label' => 'Profile Picture',
        'accept' => 'image/*',
        'maxSize' => 5, // 5MB
        'crop' => true,
        'aspectRatio' => '1:1'
    ])
    ->add('first_name', 'text', [
        'label' => 'First Name',
        'required' => true
    ])
    ->add('last_name', 'text', [
        'label' => 'Last Name',
        'required' => true
    ])
    ->add('email', 'email', [
        'label' => 'Email',
        'required' => true,
        'validation' => 'required|email|unique:users'
    ])
    ->add('password', 'password', [
        'label' => 'Password',
        'required' => true,
        'validation' => 'required|min:8|confirmed'
    ])
    ->add('password_confirmation', 'password', [
        'label' => 'Confirm Password',
        'required' => true
    ])
    ->add('birth_date', 'date', [
        'label' => 'Date of Birth',
        'validation' => 'required|date|before:today'
    ])
    ->add('terms', 'checkbox', [
        'label' => 'I agree to the Terms of Service',
        'required' => true,
        'validation' => 'required|accepted'
    ]);
```

### Helper Functions

```php
// Quick form creation
$quickForm = form_quick([
    'name' => 'text',
    'email' => ['type' => 'email', 'required' => true],
    'message' => 'textarea'
], [
    'action' => '/contact',
    'method' => 'POST'
]);

// Standalone field
$nameField = form_field('name', 'text', [
    'label' => 'Full Name',
    'required' => true
]);

// Form container
$container = form_container('my-container');

// Quick container creation
$quickContainer = form_container_quick([
    'form1' => [
        'fields' => ['name' => 'text', 'email' => 'email'],
        'containerOptions' => ['title' => 'Contact Info']
    ]
], ['name' => 'Multi-Form Container', 'tabbed' => true]);
```

## 📝 Complete Field Types Reference

### 📥 Input Fields
| Field Type | Description | Key Features |
|------------|-------------|--------------|
| `text` | Standard text input | Placeholder, validation, autocomplete |
| `email` | Email input with validation | Built-in email validation, suggestions |
| `password` | Password input | Strength meter, confirmation matching |
| `number` | Numeric input | Min/max values, step increments |
| `tel` | Telephone input | International format support |
| `url` | URL input with validation | Protocol validation, link preview |
| `search` | Search input | Autocomplete, search suggestions |
| `hidden` | Hidden form field | Secure value storage |

### 📅 Date & Time Fields
| Field Type | Description | Key Features |
|------------|-------------|--------------|
| `date` | Date picker | Min/max dates, custom formats |
| `time` | Time picker | 12/24 hour format, minute steps |
| `datetime` | Date and time picker | Timezone support, combined input |
| `week` | Week picker | ISO week format |
| `month` | Month picker | Year/month selection |
| `daterange` | Date range picker | Start/end dates, presets |

### 🎯 Selection Fields
| Field Type | Description | Key Features |
|------------|-------------|--------------|
| `select` | Dropdown select | Search, grouping, multi-select |
| `radio` | Radio button group | Inline/stacked layout |
| `checkbox` | Checkbox group | Single or multiple options |
| `tags` | Tag input | Autocomplete, custom tags, suggestions |

### 📝 Text Areas & Rich Content
| Field Type | Description | Key Features |
|------------|-------------|--------------|
| `textarea` | Multi-line text input | Auto-resize, character count |
| `richtext` | WYSIWYG editor | TinyMCE integration, custom toolbar |

### 📁 File & Media Fields
| Field Type | Description | Key Features |
|------------|-------------|--------------|
| `file` | File upload | Drag & drop, progress, validation |
| `image` | Image upload | Cropping, preview, multiple formats |
| `gallery` | Multiple image upload | Sortable, bulk upload, thumbnails |

### 🎨 Visual & Interactive Fields
| Field Type | Description | Key Features |
|------------|-------------|--------------|
| `color` | Color picker | Palette, alpha support, formats |
| `range` | Range slider | Min/max, step, value display |
| `rating` | Star rating | Half stars, custom icons, readonly |
| `map` | Location picker | Google Maps, geocoding, markers |

### 🔘 Form Controls
| Field Type | Description | Key Features |
|------------|-------------|--------------|
| `submit` | Submit button | Custom styling, loading states |
| `button` | Generic button | Click handlers, custom actions |
| `reset` | Reset button | Form clearing, confirmation |

### 🏗️ Layout & Organization
| Field Type | Description | Key Features |
|------------|-------------|--------------|
| `html` | Custom HTML content | Raw HTML, dynamic content |
| `divider` | Visual separator | Different styles, text labels |
| `group` | Field grouping | Nested fields, layouts |

## ⚙️ Configuration

### Framework Selection
```php
// Bootstrap 5 (default)
Form::create()->theme('bootstrap5');

// Bootstrap 4
Form::create()->theme('bootstrap4');

// Tailwind CSS
Form::create()->theme('tailwind');

// Custom theme
Form::create()->theme('custom');
```

### Global Configuration
Edit `config/form.php`:

```php
return [
    'default_theme' => 'bootstrap5',
    'validation' => [
        'realtime' => true,
        'debounce' => 300,
        'show_errors' => true,
    ],
    'uploads' => [
        'disk' => 'public',
        'path' => 'uploads/forms',
        'max_size' => '10MB',
        'allowed_types' => ['jpg', 'png', 'pdf', 'doc'],
    ],
    'maps' => [
        'provider' => 'google',
        'api_key' => env('GOOGLE_MAPS_API_KEY'),
        'default_zoom' => 10,
    ],
    'editor' => [
        'provider' => 'tinymce',
        'config' => [
            'height' => 300,
            'menubar' => false,
            'toolbar' => 'bold italic | link image | bullist numlist',
        ],
    ],
];
```

## ✅ Advanced Validation

### Laravel Validation Rules
```php
$form->add('email', 'email', [
    'validation' => 'required|email|unique:users,email'
]);
```

### Real-time Validation
```php
$form->add('username', 'text', [
    'validation' => 'required|min:3|unique:users',
    'realtime' => true,
    'debounce' => 500
]);
```

### Custom Validators
```php
$form->add('custom_field', 'text', [
    'validation' => ['required', new CustomRule()],
    'messages' => [
        'custom_field.required' => 'This field is mandatory'
    ]
]);
```

## 🔀 Conditional Logic & Multi-Step Forms

### Dynamic Field Visibility
```php
$form->add('account_type', 'select', [
    'label' => 'Account Type',
    'options' => [
        'personal' => 'Personal',
        'business' => 'Business'
    ]
])
->add('company_name', 'text', [
    'label' => 'Company Name',
    'show_if' => 'account_type:business',
    'validation' => 'required_if:account_type,business'
])
->add('tax_id', 'text', [
    'label' => 'Tax ID',
    'show_if' => 'account_type:business'
]);
```

### Multi-Step Forms
```php
$multiStepForm = Form::create()
    ->multiStep(true)
    ->add('step1_name', 'text', [
        'label' => 'Name',
        'step' => 1
    ])
    ->add('step2_details', 'textarea', [
        'label' => 'Details',
        'step' => 2
    ])
    ->add('step3_confirmation', 'checkbox', [
        'label' => 'Confirm',
        'step' => 3
    ]);
```

## 📁 File Upload Features

### Basic File Upload
```php
$form->add('document', 'file', [
    'label' => 'Upload Document',
    'accept' => '.pdf,.doc,.docx',
    'maxSize' => '10MB',
    'required' => true
]);
```

### Image Upload with Cropping
```php
$form->add('profile_image', 'image', [
    'label' => 'Profile Picture',
    'crop' => true,
    'aspectRatio' => '1:1',
    'minWidth' => 400,
    'maxSize' => '5MB',
    'formats' => ['jpg', 'png', 'webp']
]);
```

### Gallery Upload
```php
$form->add('photos', 'gallery', [
    'label' => 'Photo Gallery',
    'maxFiles' => 10,
    'sortable' => true,
    'preview' => true,
    'uploadUrl' => '/upload/gallery'
]);
```

## 📦 Form Container - Managing Multiple Forms

The Form Container allows you to manage multiple forms within a single interface, with support for tabbed, accordion, or stacked layouts.

### Basic Container Usage

```php
use Litepie\Form\Facades\Form;

// Create a container with multiple forms
$container = Form::container('user-settings')
    ->name('User Settings')
    ->description('Manage your account settings')
    ->tabbed(true); // Use tabbed interface

// Add forms to the container
$profileForm = $container->createForm('profile', [
    'title' => 'Profile Information',
    'description' => 'Update your personal details'
]);

$profileForm
    ->add('first_name', 'text', ['label' => 'First Name', 'required' => true])
    ->add('last_name', 'text', ['label' => 'Last Name', 'required' => true])
    ->add('email', 'email', ['label' => 'Email', 'required' => true]);

$securityForm = $container->createForm('security', [
    'title' => 'Security Settings',
    'description' => 'Manage your password and security options'
]);

$securityForm
    ->add('current_password', 'password', ['label' => 'Current Password'])
    ->add('new_password', 'password', ['label' => 'New Password'])
    ->add('confirm_password', 'password', ['label' => 'Confirm Password']);

// Render the container
{!! $container->render() !!}
```

### Quick Container Creation

```php
// Create multiple forms at once
$container = Form::quickContainer([
    'contact' => [
        'fields' => [
            'name' => 'text',
            'email' => ['type' => 'email', 'required' => true],
            'message' => ['type' => 'textarea', 'required' => true]
        ],
        'options' => ['action' => '/contact', 'method' => 'POST'],
        'containerOptions' => [
            'title' => 'Contact Information',
            'description' => 'Get in touch with us'
        ]
    ],
    'feedback' => [
        'fields' => [
            'rating' => ['type' => 'range', 'min' => 1, 'max' => 5],
            'suggestion' => 'textarea'
        ],
        'containerOptions' => [
            'title' => 'Feedback',
            'description' => 'Help us improve'
        ]
    ]
], [
    'name' => 'Contact & Feedback',
    'accordion' => true // Use accordion interface
]);
```

### Container Display Modes

```php
// Tabbed interface
$container->tabbed(true)->activeForm('step1');

// Accordion interface
$container->accordion(true);

// Stacked interface (default)
// Forms displayed one after another
```

### Container Validation Modes

```php
// Individual validation (default) - each form validated separately
$container->validationMode('individual');

// Combined validation - all forms must pass
$container->validationMode('combined');

// Sequential validation - stops at first failure
$container->validationMode('sequential');
```

### Extended Container Classes

```php
class RegistrationContainer extends \Litepie\Form\FormContainer
{
    public function __construct($app)
    {
        parent::__construct($app, 'registration');
        $this->setupRegistrationForms();
    }

    protected function setupRegistrationForms(): void
    {
        $this->name('User Registration')
             ->tabbed(true)
             ->validationMode('sequential');

        // Step 1: Personal Information
        $personal = $this->createForm('personal', [
            'title' => 'Personal Information',
            'icon' => 'user'
        ]);

        $personal
            ->add('first_name', 'text', ['required' => true])
            ->add('last_name', 'text', ['required' => true])
            ->add('email', 'email', ['required' => true]);

        // Step 2: Account Setup
        $account = $this->createForm('account', [
            'title' => 'Account Setup',
            'icon' => 'key'
        ]);

        $account
            ->add('password', 'password', ['required' => true])
            ->add('password_confirmation', 'password', ['required' => true]);
    }

    public function getProgress(): int
    {
        $totalSteps = $this->forms->count();
        $currentStep = array_search($this->getActiveForm(), $this->getFormKeys());
        return (int)(($currentStep + 1) / $totalSteps * 100);
    }
}

// Usage
$registrationContainer = new RegistrationContainer(app());
echo $registrationContainer->render();
```

📚 **See [doc/container-examples.md](doc/container-examples.md) for comprehensive container usage examples.**

## 🌐 Laravel Integration

### Controller Example
```php
class ContactController extends Controller
{
    public function create()
    {
        $form = Form::create()
            ->action(route('contact.store'))
            ->method('POST')
            ->add('name', 'text', ['required' => true])
            ->add('email', 'email', ['required' => true])
            ->add('submit', 'submit', ['value' => 'Send']);

        return view('contact.create', compact('form'));
    }

    public function store(Request $request)
    {
        // Form validation is automatic
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email'
        ]);

        // Process the form...
        
        return redirect()->back()->with('success', 'Message sent!');
    }
}
```

### Blade Template Integration
```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Contact Us</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {!! $form->render() !!}
</div>
@endsection

@push('styles')
    {!! form_include_assets(true, false) !!}
@endpush

@push('scripts')
    {!! form_include_assets(false, true) !!}
@endpush
```

## 🚀 Client-Side Framework Integration

### Convert Forms to Arrays/JSON for Vue.js, React, Angular

```php
// Convert existing form to array
$form = Form::create()
    ->add('name', 'text', ['required' => true])
    ->add('email', 'email', ['required' => true]);

$formArray = $form->toArray();
$formJson = $form->toJson();

// Or use helper functions
$formArray = form_array([
    'name' => ['type' => 'text', 'label' => 'Name', 'required' => true],
    'email' => ['type' => 'email', 'label' => 'Email', 'required' => true]
], [
    'action' => '/api/contact',
    'method' => 'POST'
]);

$formJson = form_json($fields, $options);
```

### API Endpoint for Client-Side
```php
// Return form schema as JSON for Vue/React/Angular
Route::get('/api/forms/contact', function() {
    return form_array([
        'name' => ['type' => 'text', 'label' => 'Name', 'required' => true],
        'email' => ['type' => 'email', 'label' => 'Email', 'required' => true],
        'message' => ['type' => 'textarea', 'label' => 'Message', 'required' => true]
    ], [
        'action' => '/api/contact',
        'method' => 'POST'
    ]);
});
```

### Vue.js Example
```vue
<template>
  <form @submit.prevent="submitForm">
    <div v-for="(field, name) in formFields" :key="name">
      <label>{{ field.label }}</label>
      <input 
        v-if="field.type === 'text'"
        v-model="formData[name]"
        :type="field.type"
        :required="field.required"
      />
      <textarea 
        v-if="field.type === 'textarea'"
        v-model="formData[name]"
        :required="field.required"
      ></textarea>
    </div>
    <button type="submit">Submit</button>
  </form>
</template>

<script>
export default {
  data() {
    return {
      formFields: {},
      formData: {}
    }
  },
  async mounted() {
    const response = await fetch('/api/forms/contact')
    const schema = await response.json()
    this.formFields = schema.fields
    
    // Initialize form data
    Object.keys(this.formFields).forEach(field => {
      this.$set(this.formData, field, '')
    })
  }
}
</script>
```

📚 **See [doc/client-side-examples.md](doc/client-side-examples.md) for complete Vue.js, React, and Angular integration examples.**

## 🎨 Theming & Customization

### Custom Field Templates
Create custom field templates in `resources/views/forms/`:

```blade
<!-- resources/views/forms/custom/text.blade.php -->
<div class="custom-field-wrapper">
    <label for="{{ $field->getId() }}" class="custom-label">
        {{ $field->getLabel() }}
        @if($field->isRequired())
            <span class="required">*</span>
        @endif
    </label>
    
    <input type="text" 
           name="{{ $field->getName() }}" 
           id="{{ $field->getId() }}"
           value="{{ $field->getValue() }}"
           class="custom-input {{ $field->hasErrors() ? 'error' : '' }}"
           {!! $field->getAttributesString() !!}>
    
    @if($field->hasErrors())
        <div class="error-message">{{ $field->getFirstError() }}</div>
    @endif
    
    @if($field->getHelp())
        <div class="help-text">{{ $field->getHelp() }}</div>
    @endif
</div>
```

### Custom CSS Classes
```php
$form->add('name', 'text', [
    'class' => 'custom-input large',
    'wrapper_class' => 'custom-wrapper',
    'label_class' => 'custom-label'
]);
```

## 🔧 Advanced Features

### AJAX Form Submission
```php
$form = Form::create()
    ->ajax(true)
    ->action('/api/contact')
    ->onSuccess('handleSuccess')
    ->onError('handleError')
    ->add('name', 'text', ['required' => true]);
```

### Form Validation Events
```javascript
document.addEventListener('form:validation:success', function(event) {
    console.log('Form validated successfully', event.detail);
});

document.addEventListener('form:validation:error', function(event) {
    console.log('Validation errors', event.detail.errors);
});
```

### Dynamic Field Addition
```php
$form = Form::create()
    ->add('base_field', 'text')
    ->addIf($condition, 'conditional_field', 'text')
    ->addWhen('user_type', 'business', function($form) {
        $form->add('company_name', 'text', ['required' => true]);
        $form->add('tax_id', 'text');
    });
```

## 🧪 Testing

The package includes comprehensive test coverage:

```bash
# Run all tests
composer test

# Run specific test suite
composer test -- --filter=FormBuilderTest

# Run with coverage
composer test -- --coverage-html coverage
```

### Writing Tests for Your Forms
```php
class ContactFormTest extends TestCase
{
    /** @test */
    public function it_validates_contact_form()
    {
        $form = Form::create()
            ->add('name', 'text', ['required' => true])
            ->add('email', 'email', ['required' => true]);

        $this->assertTrue($form->validate([
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]));

        $this->assertFalse($form->validate([
            'name' => '',
            'email' => 'invalid-email'
        ]));
    }
}
```

## 📚 API Reference

### FormBuilder Methods
```php
// Form configuration
$form->action(string $action)
$form->method(string $method)
$form->files(bool $enabled = true)
$form->theme(string $theme)
$form->ajax(bool $enabled = true)
$form->multiStep(bool $enabled = true)

// Field management
$form->add(string $name, string $type, array $options = [])
$form->remove(string $name)
$form->has(string $name)
$form->get(string $name)

// Data handling
$form->populate(array $data)
$form->validate(array $data)
$form->getValidationRules()

// Rendering
$form->render()
$form->renderField(string $name)
$form->renderErrors()
```

### Field Options
```php
[
    'label' => 'Field Label',
    'placeholder' => 'Enter value...',
    'help' => 'Help text',
    'required' => true,
    'validation' => 'required|string|max:255',
    'class' => 'custom-class',
    'attributes' => ['data-custom' => 'value'],
    'show_if' => 'other_field:value',
    'hide_if' => 'other_field:value',
    'value' => 'default value'
]
```

## 🤝 Contributing

We welcome contributions! Please see our [Contributing Guide](CONTRIBUTING.md) for details.

### Development Setup
```bash
git clone https://github.com/litepie/form.git
cd form
composer install
composer test
```

### Code Style
```bash
composer format      # Fix code style
composer format:check # Check code style
composer analyse     # Static analysis
composer ci          # Run all checks
```

## 📄 License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.

## ⚡ Performance & Caching

### Caching Support

Form containers support comprehensive caching to improve performance:

```php
// Enable caching with custom TTL
$container = Form::container('user-settings')
    ->enableCache(3600) // 1 hour
    ->cacheTags(['user_forms', 'settings']);

// Configure cache settings
$container->cache([
    'enabled' => true,
    'ttl' => 1800, // 30 minutes
    'driver' => 'redis',
    'tags' => ['forms', 'containers'],
]);

// Cache is automatically applied to:
// - render() - Caches full HTML output
// - renderSingleForm() - Caches individual form HTML
// - toArray() - Caches array representation
// - getVisibleForms() - Caches filtered collections

// Manual cache management
$container->clearCache(); // Clear all cache for this container
$container->disableCache(); // Temporarily disable caching
```

### Cache Configuration

Add to your `config/form.php`:

```php
'cache' => [
    'enabled' => env('FORM_CACHE_ENABLED', true),
    'ttl' => env('FORM_CACHE_TTL', 3600),
    'driver' => env('FORM_CACHE_DRIVER', 'redis'),
    'prefix' => 'form_cache',
    'tags' => ['forms', 'containers'],
    'auto_clear_on_update' => true,
],
```

### Cache Benefits

- **50-90% faster rendering** for complex forms
- **Reduced server load** with cached HTML output
- **Automatic invalidation** when forms are modified
- **Tagged cache** for efficient bulk clearing
- **Multiple drivers** (file, redis, memcached)

For detailed caching documentation, see [doc/caching.md](doc/caching.md).

## � Documentation

- **[Examples](doc/examples.md)** - Comprehensive examples for all field types and features
- **[Container Examples](doc/container-examples.md)** - Multi-form container usage and patterns
- **[Client-Side Examples](doc/client-side-examples.md)** - JavaScript integration and API usage
- **[Caching Guide](doc/caching.md)** - Performance optimization with caching

## �🔒 Security

If you discover security vulnerabilities, please send an email to security@litepie.com instead of using the issue tracker.

## 📞 Support

- 📖 [Documentation](https://litepie.com/docs/form)
- 🐛 [Issue Tracker](https://github.com/Litepie/Form/issues)
- 💬 [Discussions](https://github.com/Litepie/Form/discussions)
- 📧 [Email Support](mailto:support@litepie.com)

---

## 🏢 About

This package is part of the **Litepie** ecosystem, developed by **Renfos Technologies**. 

### Organization Structure
- **Vendor:** Litepie
- **Framework:** Lavalite
- **Company:** Renfos Technologies

### Links & Resources
- 🌐 **Website:** [https://lavalite.org](https://lavalite.org)
- 📚 **Documentation:** [https://docs.lavalite.org](https://docs.lavalite.org)
- 💼 **Company:** [https://renfos.com](https://renfos.com)
- 📧 **Support:** [support@lavalite.org](mailto:support@lavalite.org)

---

<div align="center">
  <p><strong>Built with ❤️ by Renfos Technologies</strong></p>
  <p><em>Empowering developers with robust Laravel solutions</em></p>
</div>
