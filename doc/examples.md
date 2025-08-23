# Litepie Form Examples

## Basic Usage Examples

### Simple Contact Form

```php
<?php

use Litepie\Form\Facades\Form;

// Create a simple contact form
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
        'placeholder' => 'Enter your email',
        'validation' => 'required|email'
    ])
    ->add('phone', 'tel', [
        'label' => 'Phone Number',
        'placeholder' => '+1 (555) 123-4567',
        'validation' => 'nullable|string'
    ])
    ->add('subject', 'select', [
        'label' => 'Subject',
        'required' => true,
        'options' => [
            'general' => 'General Inquiry',
            'support' => 'Technical Support',
            'sales' => 'Sales Question',
            'other' => 'Other'
        ]
    ])
    ->add('message', 'textarea', [
        'label' => 'Message',
        'required' => true,
        'placeholder' => 'Enter your message here...',
        'validation' => 'required|string|min:10',
        'rows' => 5
    ])
    ->add('submit', 'submit', [
        'value' => 'Send Message',
        'class' => 'btn btn-primary'
    ]);

// Render the form
echo $contactForm->render();
```

### User Registration Form with File Upload

```php
<?php

use Litepie\Form\Facades\Form;

$registrationForm = Form::create()
    ->action('/register')
    ->method('POST')
    ->files(true) // Enable file uploads
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
    ])
    ->add('avatar', 'image', [
        'label' => 'Profile Picture',
        'accept' => 'image/*',
        'maxSize' => 5, // 5MB
        'crop' => true,
        'aspectRatio' => '1:1',
        'help' => 'Upload a profile picture (max 5MB)'
    ])
    ->add('birth_date', 'date', [
        'label' => 'Date of Birth',
        'validation' => 'required|date|before:today'
    ])
    ->add('terms', 'checkbox', [
        'label' => 'I agree to the Terms of Service',
        'required' => true,
        'validation' => 'required|accepted'
    ])
    ->add('submit', 'submit', [
        'value' => 'Create Account',
        'class' => 'btn btn-success'
    ]);

echo $registrationForm->render();
```

## Advanced Examples

### Multi-Step Form

```php
<?php

use Litepie\Form\Facades\Form;

$multiStepForm = Form::create()
    ->action('/application')
    ->method('POST')
    ->files(true)
    ->attribute('class', 'litepie-form multi-step-form');

// Step 1: Personal Information
$multiStepForm->add('step1_title', 'html', [
    'content' => '<h3>Step 1: Personal Information</h3>'
]);

$multiStepForm->add('personal_info_group', 'group', [
    'label' => 'Personal Details',
    'fields' => [
        'first_name' => [
            'type' => 'text',
            'label' => 'First Name',
            'required' => true
        ],
        'last_name' => [
            'type' => 'text',
            'label' => 'Last Name',
            'required' => true
        ],
        'email' => [
            'type' => 'email',
            'label' => 'Email',
            'required' => true
        ]
    ]
]);

// Step 2: Professional Information
$multiStepForm->add('step2_title', 'html', [
    'content' => '<h3>Step 2: Professional Information</h3>',
    'show_if' => 'step:2'
]);

$multiStepForm->add('company', 'text', [
    'label' => 'Company Name',
    'show_if' => 'step:2'
])
->add('position', 'text', [
    'label' => 'Job Title',
    'show_if' => 'step:2'
])
->add('experience', 'select', [
    'label' => 'Years of Experience',
    'options' => [
        '0-1' => '0-1 years',
        '2-5' => '2-5 years',
        '6-10' => '6-10 years',
        '10+' => '10+ years'
    ],
    'show_if' => 'step:2'
]);

// Step 3: Documents
$multiStepForm->add('step3_title', 'html', [
    'content' => '<h3>Step 3: Documents</h3>',
    'show_if' => 'step:3'
]);

$multiStepForm->add('resume', 'file', [
    'label' => 'Resume/CV',
    'accept' => '.pdf,.doc,.docx',
    'required' => true,
    'show_if' => 'step:3'
])
->add('cover_letter', 'file', [
    'label' => 'Cover Letter',
    'accept' => '.pdf,.doc,.docx',
    'show_if' => 'step:3'
]);

// Navigation buttons
$multiStepForm->add('prev_btn', 'button', [
    'value' => 'Previous',
    'class' => 'btn btn-secondary btn-prev',
    'show_if' => 'step:>1'
])
->add('next_btn', 'button', [
    'value' => 'Next',
    'class' => 'btn btn-primary btn-next',
    'hide_if' => 'step:3'
])
->add('submit', 'submit', [
    'value' => 'Submit Application',
    'class' => 'btn btn-success',
    'show_if' => 'step:3'
]);
```

### Dynamic Form with Conditional Logic

```php
<?php

use Litepie\Form\Facades\Form;

$dynamicForm = Form::create()
    ->action('/survey')
    ->method('POST')
    ->add('user_type', 'radio', [
        'label' => 'I am a:',
        'options' => [
            'student' => 'Student',
            'professional' => 'Working Professional',
            'business' => 'Business Owner'
        ],
        'required' => true
    ])
    
    // Student-specific fields
    ->add('school', 'text', [
        'label' => 'School/University',
        'show_if' => 'user_type:student',
        'validation' => 'required_if:user_type,student'
    ])
    ->add('graduation_year', 'number', [
        'label' => 'Expected Graduation Year',
        'show_if' => 'user_type:student',
        'min' => date('Y'),
        'max' => date('Y') + 10
    ])
    
    // Professional-specific fields
    ->add('company_name', 'text', [
        'label' => 'Company Name',
        'show_if' => 'user_type:professional',
        'validation' => 'required_if:user_type,professional'
    ])
    ->add('job_title', 'text', [
        'label' => 'Job Title',
        'show_if' => 'user_type:professional'
    ])
    ->add('salary_range', 'select', [
        'label' => 'Annual Salary Range',
        'show_if' => 'user_type:professional',
        'options' => [
            '0-30k' => '$0 - $30,000',
            '30k-50k' => '$30,000 - $50,000',
            '50k-75k' => '$50,000 - $75,000',
            '75k-100k' => '$75,000 - $100,000',
            '100k+' => '$100,000+'
        ]
    ])
    
    // Business owner-specific fields
    ->add('business_name', 'text', [
        'label' => 'Business Name',
        'show_if' => 'user_type:business',
        'validation' => 'required_if:user_type,business'
    ])
    ->add('business_type', 'select', [
        'label' => 'Business Type',
        'show_if' => 'user_type:business',
        'options' => [
            'sole_proprietorship' => 'Sole Proprietorship',
            'partnership' => 'Partnership',
            'corporation' => 'Corporation',
            'llc' => 'LLC'
        ]
    ])
    ->add('employees', 'select', [
        'label' => 'Number of Employees',
        'show_if' => 'user_type:business',
        'options' => [
            '1' => 'Just me',
            '2-10' => '2-10 employees',
            '11-50' => '11-50 employees',
            '51-200' => '51-200 employees',
            '200+' => '200+ employees'
        ]
    ])
    
    // Common fields
    ->add('interests', 'checkbox', [
        'label' => 'Areas of Interest (select all that apply)',
        'options' => [
            'technology' => 'Technology',
            'finance' => 'Finance',
            'marketing' => 'Marketing',
            'design' => 'Design',
            'education' => 'Education',
            'healthcare' => 'Healthcare'
        ]
    ])
    ->add('newsletter', 'checkbox', [
        'label' => 'Subscribe to our newsletter',
        'value' => '1'
    ])
    ->add('submit', 'submit', [
        'value' => 'Submit Survey',
        'class' => 'btn btn-primary'
    ]);
```

### E-commerce Product Form

```php
<?php

use Litepie\Form\Facades\Form;

$productForm = Form::create()
    ->action('/admin/products')
    ->method('POST')
    ->files(true)
    ->add('name', 'text', [
        'label' => 'Product Name',
        'required' => true,
        'validation' => 'required|string|max:255'
    ])
    ->add('sku', 'text', [
        'label' => 'SKU',
        'required' => true,
        'validation' => 'required|string|unique:products,sku'
    ])
    ->add('description', 'richtext', [
        'label' => 'Description',
        'required' => true,
        'height' => 400,
        'config' => [
            'toolbar' => 'bold italic underline | link image | bullist numlist',
            'menubar' => false
        ]
    ])
    ->add('category_id', 'select', [
        'label' => 'Category',
        'required' => true,
        'options' => [
            1 => 'Electronics',
            2 => 'Clothing',
            3 => 'Books',
            4 => 'Home & Garden'
        ]
    ])
    ->add('price', 'number', [
        'label' => 'Price ($)',
        'required' => true,
        'min' => 0,
        'step' => 0.01,
        'validation' => 'required|numeric|min:0'
    ])
    ->add('sale_price', 'number', [
        'label' => 'Sale Price ($)',
        'min' => 0,
        'step' => 0.01,
        'validation' => 'nullable|numeric|min:0|lt:price'
    ])
    ->add('stock_quantity', 'number', [
        'label' => 'Stock Quantity',
        'required' => true,
        'min' => 0,
        'validation' => 'required|integer|min:0'
    ])
    ->add('weight', 'number', [
        'label' => 'Weight (kg)',
        'step' => 0.01,
        'validation' => 'nullable|numeric|min:0'
    ])
    ->add('dimensions', 'group', [
        'label' => 'Dimensions (cm)',
        'fields' => [
            'length' => ['type' => 'number', 'placeholder' => 'Length'],
            'width' => ['type' => 'number', 'placeholder' => 'Width'],
            'height' => ['type' => 'number', 'placeholder' => 'Height']
        ]
    ])
    ->add('images', 'gallery', [
        'label' => 'Product Images',
        'maxFiles' => 10,
        'accept' => 'image/*',
        'sortable' => true,
        'help' => 'Upload up to 10 product images. First image will be the main image.'
    ])
    ->add('status', 'select', [
        'label' => 'Status',
        'options' => [
            'draft' => 'Draft',
            'active' => 'Active',
            'inactive' => 'Inactive'
        ],
        'value' => 'draft'
    ])
    ->add('featured', 'checkbox', [
        'label' => 'Featured Product',
        'value' => '1'
    ])
    ->add('tags', 'tags', [
        'label' => 'Tags',
        'placeholder' => 'Enter tags...',
        'help' => 'Press Enter to add tags'
    ])
    ->add('submit', 'submit', [
        'value' => 'Save Product',
        'class' => 'btn btn-success'
    ])
    ->add('save_draft', 'button', [
        'value' => 'Save as Draft',
        'class' => 'btn btn-secondary',
        'onclick' => 'document.getElementById("status").value="draft"; this.form.submit();'
    ]);
```

## Usage in Laravel Controller

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Litepie\Form\Facades\Form;

class ContactController extends Controller
{
    public function show()
    {
        $form = Form::create()
            ->action(route('contact.store'))
            ->method('POST')
            ->add('name', 'text', [
                'label' => 'Name',
                'required' => true,
                'validation' => 'required|string|max:255'
            ])
            ->add('email', 'email', [
                'label' => 'Email',
                'required' => true,
                'validation' => 'required|email'
            ])
            ->add('message', 'textarea', [
                'label' => 'Message',
                'required' => true,
                'validation' => 'required|string|min:10'
            ])
            ->add('submit', 'submit', [
                'value' => 'Send Message',
                'class' => 'btn btn-primary'
            ]);

        return view('contact', compact('form'));
    }

    public function store(Request $request)
    {
        // Validation is handled by the form builder
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string|min:10'
        ]);

        // Process the form data
        // Send email, save to database, etc.

        return redirect()->back()->with('success', 'Message sent successfully!');
    }
}
```

## Usage in Blade Template

```blade
<!DOCTYPE html>
<html>
<head>
    <title>Contact Form</title>
    {!! form_include_assets() !!}
</head>
<body>
    <div class="container">
        <h1>Contact Us</h1>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                {!! $form !!}
            </div>
        </div>
    </div>
</body>
</html>
```

## Helper Functions Usage

```php
<?php

// Quick form creation
$quickForm = form_quick([
    'name' => 'text',
    'email' => ['type' => 'email', 'required' => true],
    'message' => 'textarea'
], [
    'action' => '/contact',
    'method' => 'POST'
]);

// Standalone field creation
$nameField = form_field('name', 'text', [
    'label' => 'Full Name',
    'required' => true
]);

// Include form assets in layout
echo form_include_assets(); // Includes both CSS and JS
echo form_include_assets(true, false); // CSS only
echo form_include_assets(false, true); // JS only
```
