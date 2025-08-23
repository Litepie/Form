# Form Container Examples

The Form Container allows you to manage multiple forms within a single container, providing various display modes (tabbed, accordion, stacked) and advanced features like sequential validation.

## Basic Usage

### Creating a Simple Container

```php
<?php

use Litepie\Form\Facades\Form;

// Create a basic container
$container = Form::container('user-profile')
    ->name('User Profile Settings')
    ->description('Manage your profile information and preferences');

// Add forms to the container
$personalForm = $container->createForm('personal', [
    'title' => 'Personal Information',
    'description' => 'Basic personal details'
]);

$personalForm
    ->add('first_name', 'text', [
        'label' => 'First Name',
        'required' => true
    ])
    ->add('last_name', 'text', [
        'label' => 'Last Name', 
        'required' => true
    ])
    ->add('email', 'email', [
        'label' => 'Email Address',
        'required' => true,
        'validation' => 'required|email|unique:users,email'
    ]);

$preferencesForm = $container->createForm('preferences', [
    'title' => 'Preferences',
    'description' => 'Customize your experience'
]);

$preferencesForm
    ->add('newsletter', 'checkbox', [
        'label' => 'Subscribe to newsletter'
    ])
    ->add('theme', 'select', [
        'label' => 'Theme',
        'options' => [
            'light' => 'Light',
            'dark' => 'Dark',
            'auto' => 'Auto'
        ]
    ]);

// Render the container
echo $container->render();
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
        'options' => [
            'action' => '/contact',
            'method' => 'POST'
        ],
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
    'tabbed' => true
]);
```

## Display Modes

### Tabbed Interface

```php
$container = Form::container()
    ->name('Multi-Step Registration')
    ->tabbed(true)
    ->activeForm('step1'); // Set default active tab

$step1 = $container->createForm('step1', ['title' => 'Personal Info']);
$step2 = $container->createForm('step2', ['title' => 'Account Details']);
$step3 = $container->createForm('step3', ['title' => 'Preferences']);

// Add fields to each form...
```

### Accordion Interface

```php
$container = Form::container()
    ->name('Settings Dashboard')
    ->accordion(true);

$general = $container->createForm('general', [
    'title' => 'General Settings',
    'collapsible' => true,
    'collapsed' => false // Open by default
]);

$privacy = $container->createForm('privacy', [
    'title' => 'Privacy Settings',
    'collapsible' => true,
    'collapsed' => true // Closed by default
]);

$notifications = $container->createForm('notifications', [
    'title' => 'Notification Settings',
    'collapsible' => true,
    'collapsed' => true
]);
```

### Stacked Interface (Default)

```php
$container = Form::container()
    ->name('Complete Application');

// Forms will be displayed one after another
$container->createForm('applicant', ['title' => 'Applicant Information']);
$container->createForm('experience', ['title' => 'Work Experience']);
$container->createForm('documents', ['title' => 'Supporting Documents']);
```

## Data Population

### Structured Data Population

```php
$container = Form::container();

$personalForm = $container->createForm('personal');
$personalForm->add('name', 'text')->add('email', 'email');

$addressForm = $container->createForm('address');
$addressForm->add('street', 'text')->add('city', 'text');

// Populate with structured data
$data = [
    'personal' => [
        'name' => 'John Doe',
        'email' => 'john@example.com'
    ],
    'address' => [
        'street' => '123 Main St',
        'city' => 'Anytown'
    ]
];

$container->populate($data);
```

### Global Data Population

```php
// All forms receive the same data
$globalData = [
    'user_id' => 123,
    'name' => 'John Doe',
    'email' => 'john@example.com'
];

$container->populate($globalData);
```

## Validation Modes

### Individual Validation (Default)

```php
$container = Form::container()
    ->validationMode('individual');

// Each form is validated independently
$results = $container->validate($data);
// Returns: ['form1' => true, 'form2' => false, ...]
```

### Combined Validation

```php
$container = Form::container()
    ->validationMode('combined');

$results = $container->validate($data);
// Returns individual results plus combined result:
// [
//     'form1' => true,
//     'form2' => false,
//     '_combined' => [
//         'valid' => false,
//         'errors' => ['form2' => [...]]
//     ]
// ]
```

### Sequential Validation

```php
$container = Form::container()
    ->validationMode('sequential');

// Stops at first validation failure
$results = $container->validate($data);
// If form1 fails, form2 and subsequent forms won't be validated
```

## Advanced Features

### Form Reordering

```php
$container = Form::container();

$container->createForm('third');
$container->createForm('first');
$container->createForm('second');

// Reorder forms
$container->reorderForms(['first', 'second', 'third']);
```

### Conditional Form Visibility

```php
$container->addForm('basic', $basicForm, [
    'title' => 'Basic Information',
    'visible' => true
]);

$container->addForm('advanced', $advancedForm, [
    'title' => 'Advanced Settings',
    'visible' => false, // Hidden by default
    'conditions' => ['user_type' => 'admin']
]);

// Get only visible forms
$visibleForms = $container->getVisibleForms();
```

### Form Metadata

```php
$container->addForm('profile', $profileForm, [
    'title' => 'Profile Settings',
    'description' => 'Manage your profile information',
    'icon' => 'user-circle',
    'badge' => 'Required',
    'class' => 'priority-high',
    'order' => 1
]);
```

## Extended Container Classes

### Custom Registration Container

```php
class RegistrationContainer extends \Litepie\Form\FormContainer
{
    public function __construct($app)
    {
        parent::__construct($app, 'registration');
        $this->setupRegistrationFlow();
    }

    protected function setupRegistrationFlow(): void
    {
        $this->name('User Registration')
             ->description('Create your account in 3 easy steps')
             ->tabbed(true)
             ->validationMode('sequential');

        // Step 1: Personal Information
        $personal = $this->createForm('personal', [
            'title' => 'Personal Information',
            'description' => 'Tell us about yourself',
            'icon' => 'user'
        ]);

        $personal
            ->action('/register/personal')
            ->add('first_name', 'text', [
                'label' => 'First Name',
                'required' => true,
                'validation' => 'required|string|max:50'
            ])
            ->add('last_name', 'text', [
                'label' => 'Last Name',
                'required' => true,
                'validation' => 'required|string|max:50'
            ])
            ->add('birth_date', 'date', [
                'label' => 'Date of Birth',
                'required' => true,
                'validation' => 'required|date|before:today'
            ]);

        // Step 2: Account Details
        $account = $this->createForm('account', [
            'title' => 'Account Details',
            'description' => 'Setup your login credentials',
            'icon' => 'key'
        ]);

        $account
            ->action('/register/account')
            ->add('email', 'email', [
                'label' => 'Email Address',
                'required' => true,
                'validation' => 'required|email|unique:users,email'
            ])
            ->add('password', 'password', [
                'label' => 'Password',
                'required' => true,
                'validation' => 'required|string|min:8|confirmed'
            ])
            ->add('password_confirmation', 'password', [
                'label' => 'Confirm Password',
                'required' => true
            ]);

        // Step 3: Preferences
        $preferences = $this->createForm('preferences', [
            'title' => 'Preferences',
            'description' => 'Customize your experience',
            'icon' => 'settings'
        ]);

        $preferences
            ->action('/register/preferences')
            ->add('newsletter', 'checkbox', [
                'label' => 'Subscribe to newsletter'
            ])
            ->add('notifications', 'select', [
                'label' => 'Notification Preference',
                'options' => [
                    'all' => 'All notifications',
                    'important' => 'Important only',
                    'none' => 'No notifications'
                ]
            ]);
    }

    public function getProgress(): int
    {
        $totalSteps = $this->forms->count();
        $currentStepIndex = array_search($this->getActiveForm(), $this->getFormKeys());
        return (int)(($currentStepIndex + 1) / $totalSteps * 100);
    }

    public function validateAge(array $data): bool
    {
        if (isset($data['personal']['birth_date'])) {
            $age = \Carbon\Carbon::parse($data['personal']['birth_date'])->age;
            return $age >= 13;
        }
        return true;
    }
}

// Usage
$registrationContainer = new RegistrationContainer(app());
echo $registrationContainer->render();
```

### E-commerce Checkout Container

```php
class CheckoutContainer extends \Litepie\Form\FormContainer
{
    public function __construct($app)
    {
        parent::__construct($app, 'checkout');
        $this->setupCheckoutFlow();
    }

    protected function setupCheckoutFlow(): void
    {
        $this->name('Checkout Process')
             ->accordion(true)
             ->validationMode('sequential');

        // Shipping Information
        $shipping = $this->createForm('shipping', [
            'title' => 'Shipping Information',
            'icon' => 'truck'
        ]);

        $shipping
            ->add('shipping_name', 'text', [
                'label' => 'Full Name',
                'required' => true
            ])
            ->add('shipping_address', 'text', [
                'label' => 'Address',
                'required' => true
            ])
            ->add('shipping_city', 'text', [
                'label' => 'City',
                'required' => true
            ])
            ->add('shipping_zip', 'text', [
                'label' => 'ZIP Code',
                'required' => true
            ]);

        // Payment Information
        $payment = $this->createForm('payment', [
            'title' => 'Payment Information',
            'icon' => 'credit-card'
        ]);

        $payment
            ->add('payment_method', 'radio', [
                'label' => 'Payment Method',
                'required' => true,
                'options' => [
                    'credit_card' => 'Credit Card',
                    'paypal' => 'PayPal',
                    'bank_transfer' => 'Bank Transfer'
                ]
            ])
            ->add('card_number', 'text', [
                'label' => 'Card Number',
                'show_if' => 'payment_method:credit_card'
            ])
            ->add('card_expiry', 'text', [
                'label' => 'Expiry Date',
                'show_if' => 'payment_method:credit_card'
            ])
            ->add('card_cvv', 'text', [
                'label' => 'CVV',
                'show_if' => 'payment_method:credit_card'
            ]);

        // Order Review
        $review = $this->createForm('review', [
            'title' => 'Order Review',
            'icon' => 'check-circle'
        ]);

        $review
            ->add('terms', 'checkbox', [
                'label' => 'I agree to the terms and conditions',
                'required' => true,
                'validation' => 'required|accepted'
            ])
            ->add('marketing', 'checkbox', [
                'label' => 'Send me marketing emails'
            ]);
    }
}
```

## Client-Side Integration

### Convert to Array/JSON for Vue.js, React, Angular

```php
$container = Form::container()
    ->name('Dynamic Forms')
    ->tabbed(true);

// Add forms...

// Convert to array for client-side frameworks
$containerArray = $container->toArray();

// API endpoint
Route::get('/api/forms/container/{id}', function($id) {
    $container = // ... load container
    return response()->json($container->toArray());
});
```

### Vue.js Component Integration

```vue
<template>
  <div class="form-container">
    <h2>{{ container.name }}</h2>
    <p v-if="container.description">{{ container.description }}</p>
    
    <!-- Tabbed Interface -->
    <div v-if="container.tabbed" class="tabs">
      <nav class="tab-nav">
        <button 
          v-for="(form, key) in forms" 
          :key="key"
          @click="activeForm = key"
          :class="{ active: activeForm === key }"
          class="tab-button"
        >
          {{ form.meta.title }}
        </button>
      </nav>
      
      <div class="tab-content">
        <dynamic-form 
          v-for="(form, key) in forms"
          :key="key"
          v-show="activeForm === key"
          :form-schema="form"
          @submit="handleFormSubmit(key, $event)"
        />
      </div>
    </div>
    
    <!-- Accordion Interface -->
    <div v-else-if="container.accordion" class="accordion">
      <div 
        v-for="(form, key) in forms" 
        :key="key"
        class="accordion-item"
      >
        <button 
          @click="toggleAccordion(key)"
          class="accordion-header"
        >
          {{ form.meta.title }}
        </button>
        <div v-show="openAccordions.includes(key)" class="accordion-content">
          <dynamic-form 
            :form-schema="form"
            @submit="handleFormSubmit(key, $event)"
          />
        </div>
      </div>
    </div>
    
    <!-- Stacked Interface -->
    <div v-else class="stacked-forms">
      <div 
        v-for="(form, key) in forms" 
        :key="key"
        class="form-section"
      >
        <h3 v-if="form.meta.title">{{ form.meta.title }}</h3>
        <p v-if="form.meta.description">{{ form.meta.description }}</p>
        <dynamic-form 
          :form-schema="form"
          @submit="handleFormSubmit(key, $event)"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    containerId: String
  },
  data() {
    return {
      container: {},
      forms: {},
      activeForm: null,
      openAccordions: []
    }
  },
  async mounted() {
    const response = await fetch(`/api/forms/container/${this.containerId}`)
    const data = await response.json()
    
    this.container = data.container
    this.forms = data.forms
    this.activeForm = this.container.activeForm || Object.keys(this.forms)[0]
  },
  methods: {
    handleFormSubmit(formKey, data) {
      // Handle form submission
      console.log(`Form ${formKey} submitted:`, data)
    },
    
    toggleAccordion(key) {
      if (this.openAccordions.includes(key)) {
        this.openAccordions = this.openAccordions.filter(k => k !== key)
      } else {
        this.openAccordions.push(key)
      }
    }
  }
}
</script>
```

## Laravel Controller Integration

```php
class FormContainerController extends Controller
{
    public function showRegistration()
    {
        $container = Form::container('registration')
            ->name('Create Account')
            ->tabbed(true);

        // Setup forms...
        
        return view('registration', compact('container'));
    }

    public function processRegistration(Request $request)
    {
        $container = $this->buildRegistrationContainer();
        
        // Validate all forms
        $results = $container->validate($request->all());
        
        if ($container->getValidationMode() === 'combined' && !$results['_combined']['valid']) {
            return back()->withErrors($results['_combined']['errors']);
        }

        // Process valid data...
        
        return redirect()->route('dashboard');
    }

    public function getContainerApi($id)
    {
        $container = $this->loadContainer($id);
        return response()->json($container->toArray());
    }
}
```

This Form Container system provides a powerful way to manage multiple forms with different display modes, validation strategies, and client-side integration capabilities. It's perfect for complex workflows like multi-step registration, settings dashboards, checkout processes, and any scenario where you need to manage multiple related forms together.

## Returning Single Forms from Container

Sometimes you need to work with just one form from a container. Here are various ways to achieve this:

### Basic Single Form Access

```php
// Create a container with multiple forms
$container = Form::container('user-management')
    ->name('User Management');

$personalForm = $container->createForm('personal', [
    'title' => 'Personal Information'
]);
$personalForm->add('name', 'text', ['required' => true])
             ->add('email', 'email', ['required' => true]);

$settingsForm = $container->createForm('settings', [
    'title' => 'Account Settings'
]);
$settingsForm->add('theme', 'select', ['options' => ['light' => 'Light', 'dark' => 'Dark']])
             ->add('notifications', 'checkbox', ['label' => 'Enable notifications']);

// Get only the personal form
$singleForm = $container->getSingleForm('personal');
echo $singleForm->render();

// Or render directly
echo $container->renderSingleForm('personal');
```

### Using Helper Functions

```php
// Get single form using helper
$personalForm = form_container_single($container, 'personal');

// Render single form using helper
echo form_container_render_single($container, 'personal');
```

### Conditional Single Form Display

```php
// Display different forms based on user role
$container = Form::container('admin-panel');

$container->createForm('basic', ['title' => 'Basic Settings']);
$container->createForm('advanced', ['title' => 'Advanced Settings']);
$container->createForm('super_admin', ['title' => 'Super Admin Settings']);

// Show appropriate form based on user role
$userRole = auth()->user()->role;

switch ($userRole) {
    case 'admin':
        echo $container->renderSingleForm('advanced');
        break;
    case 'super_admin':
        echo $container->renderSingleForm('super_admin');
        break;
    default:
        echo $container->renderSingleForm('basic');
}
```

### Filter Container to Show Specific Forms

```php
$container = Form::container('multi-step-wizard');

$container->createForm('step1', ['title' => 'Personal Info']);
$container->createForm('step2', ['title' => 'Address Info']);
$container->createForm('step3', ['title' => 'Payment Info']);
$container->createForm('step4', ['title' => 'Review']);

// Show only certain steps based on user progress
$userProgress = session('wizard_progress', 1);

if ($userProgress === 1) {
    // Show only step 1
    $singleStepContainer = $container->extractForms(['step1']);
    echo $singleStepContainer->render();
} elseif ($userProgress === 2) {
    // Show steps 1 and 2
    $progressContainer = $container->extractForms(['step1', 'step2']);
    echo $progressContainer->render();
}
```

### API Endpoints for Single Forms

```php
// Laravel Controller
class FormApiController extends Controller
{
    public function getSingleForm($containerId, $formKey)
    {
        $container = $this->loadContainer($containerId);
        
        // Return single form as JSON
        return response()->json($container->getSingleFormArray($formKey));
    }
    
    public function renderSingleForm($containerId, $formKey)
    {
        $container = $this->loadContainer($containerId);
        
        // Return rendered HTML
        return response($container->renderSingleForm($formKey));
    }
    
    public function getConditionalForm($containerId, Request $request)
    {
        $container = $this->loadContainer($containerId);
        $userType = $request->get('user_type', 'basic');
        
        // Return different forms based on conditions
        switch ($userType) {
            case 'premium':
                return response()->json($container->getSingleFormArray('premium_settings'));
            case 'enterprise':
                return response()->json($container->getSingleFormArray('enterprise_settings'));
            default:
                return response()->json($container->getSingleFormArray('basic_settings'));
        }
    }
}

// Routes
Route::get('/api/containers/{containerId}/forms/{formKey}', [FormApiController::class, 'getSingleForm']);
Route::get('/api/containers/{containerId}/forms/{formKey}/render', [FormApiController::class, 'renderSingleForm']);
Route::get('/api/containers/{containerId}/conditional-form', [FormApiController::class, 'getConditionalForm']);
```

### Dynamic Single Form Selection

```php
class DynamicFormContainer extends \Litepie\Form\FormContainer
{
    public function __construct($app)
    {
        parent::__construct($app, 'dynamic-forms');
        $this->setupForms();
    }

    protected function setupForms(): void
    {
        // Basic user form
        $basicForm = $this->createForm('basic_profile', [
            'title' => 'Basic Profile',
            'conditions' => ['user_type' => 'basic']
        ]);
        $basicForm->add('name', 'text')
                  ->add('email', 'email');

        // Professional user form
        $proForm = $this->createForm('pro_profile', [
            'title' => 'Professional Profile',
            'conditions' => ['user_type' => 'professional']
        ]);
        $proForm->add('name', 'text')
                ->add('email', 'email')
                ->add('company', 'text')
                ->add('job_title', 'text');

        // Enterprise user form
        $enterpriseForm = $this->createForm('enterprise_profile', [
            'title' => 'Enterprise Profile',
            'conditions' => ['user_type' => 'enterprise']
        ]);
        $enterpriseForm->add('name', 'text')
                      ->add('email', 'email')
                      ->add('company', 'text')
                      ->add('department', 'text')
                      ->add('employee_count', 'select', [
                          'options' => [
                              '1-10' => '1-10 employees',
                              '11-50' => '11-50 employees',
                              '51-200' => '51-200 employees',
                              '200+' => '200+ employees'
                          ]
                      ]);
    }

    public function getFormForUserType(string $userType): ?FormBuilder
    {
        $formMap = [
            'basic' => 'basic_profile',
            'professional' => 'pro_profile',
            'enterprise' => 'enterprise_profile'
        ];

        $formKey = $formMap[$userType] ?? 'basic_profile';
        return $this->getSingleForm($formKey);
    }

    public function renderForUserType(string $userType): string
    {
        $form = $this->getFormForUserType($userType);
        return $form ? $form->render() : '<p>No form available for this user type.</p>';
    }
}

// Usage
$dynamicContainer = new DynamicFormContainer(app());

// Get form based on current user type
$userType = auth()->user()->subscription_type ?? 'basic';
echo $dynamicContainer->renderForUserType($userType);
```

### AJAX Single Form Loading

```php
// JavaScript example for dynamic form loading
class FormLoader {
    constructor(containerId) {
        this.containerId = containerId;
        this.currentForm = null;
    }

    async loadForm(formKey) {
        try {
            const response = await fetch(`/api/containers/${this.containerId}/forms/${formKey}`);
            const formData = await response.json();
            
            this.currentForm = formData;
            this.renderForm(formData);
        } catch (error) {
            console.error('Error loading form:', error);
        }
    }

    renderForm(formData) {
        const container = document.getElementById('dynamic-form-container');
        
        // Clear existing content
        container.innerHTML = '';
        
        // Add form title
        if (formData.meta.title) {
            const title = document.createElement('h3');
            title.textContent = formData.meta.title;
            container.appendChild(title);
        }

        // Render form fields dynamically...
        // (Implementation depends on your frontend framework)
    }

    async switchForm(formKey) {
        await this.loadForm(formKey);
    }
}

// Usage
const formLoader = new FormLoader('user-settings');

// Load different forms based on user selection
document.getElementById('form-selector').addEventListener('change', (e) => {
    formLoader.switchForm(e.target.value);
});
```

### Multi-Tenant Single Form Access

```php
class TenantFormContainer extends \Litepie\Form\FormContainer
{
    protected $tenant;

    public function __construct($app, $tenant)
    {
        parent::__construct($app, "tenant-{$tenant->id}-forms");
        $this->tenant = $tenant;
        $this->setupTenantForms();
    }

    protected function setupTenantForms(): void
    {
        // Different forms based on tenant features
        if ($this->tenant->hasFeature('basic_profile')) {
            $this->createBasicProfileForm();
        }

        if ($this->tenant->hasFeature('advanced_settings')) {
            $this->createAdvancedSettingsForm();
        }

        if ($this->tenant->hasFeature('billing')) {
            $this->createBillingForm();
        }
    }

    public function getAvailableForm(): ?FormBuilder
    {
        // Return the first available form for this tenant
        $availableForms = $this->getVisibleForms();
        
        if ($availableForms->isEmpty()) {
            return null;
        }

        $firstForm = $availableForms->first();
        return $firstForm['form'];
    }

    public function getFormByFeature(string $feature): ?FormBuilder
    {
        $featureFormMap = [
            'basic_profile' => 'basic_profile',
            'advanced_settings' => 'advanced_settings',
            'billing' => 'billing'
        ];

        if (!$this->tenant->hasFeature($feature)) {
            return null;
        }

        $formKey = $featureFormMap[$feature] ?? null;
        return $formKey ? $this->getSingleForm($formKey) : null;
    }
}
```

### Wizard-Style Single Form Progression

```php
class WizardFormContainer extends \Litepie\Form\FormContainer
{
    protected $currentStep = 1;
    protected $maxSteps = 4;

    public function getCurrentStepForm(): ?FormBuilder
    {
        return $this->getSingleForm("step{$this->currentStep}");
    }

    public function nextStep(): bool
    {
        if ($this->currentStep < $this->maxSteps) {
            $this->currentStep++;
            return true;
        }
        return false;
    }

    public function previousStep(): bool
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            return true;
        }
        return false;
    }

    public function renderCurrentStep(): string
    {
        $form = $this->getCurrentStepForm();
        
        if (!$form) {
            return '<p>Step not found.</p>';
        }

        $html = "<div class='wizard-step' data-step='{$this->currentStep}'>";
        $html .= "<h3>Step {$this->currentStep} of {$this->maxSteps}</h3>";
        $html .= $form->render();
        
        // Add navigation buttons
        $html .= "<div class='wizard-navigation'>";
        if ($this->currentStep > 1) {
            $html .= "<button type='button' class='btn btn-secondary' onclick='previousStep()'>Previous</button>";
        }
        if ($this->currentStep < $this->maxSteps) {
            $html .= "<button type='button' class='btn btn-primary' onclick='nextStep()'>Next</button>";
        } else {
            $html .= "<button type='submit' class='btn btn-success'>Complete</button>";
        }
        $html .= "</div>";
        $html .= "</div>";

        return $html;
    }

    public function getProgress(): int
    {
        return (int)(($this->currentStep / $this->maxSteps) * 100);
    }
}
```

These examples show various ways to work with single forms from a container, including conditional rendering, API endpoints, dynamic loading, multi-tenant scenarios, and wizard-style progression. The key is that you maintain the organizational benefits of having multiple forms in a container while having the flexibility to work with individual forms when needed.
