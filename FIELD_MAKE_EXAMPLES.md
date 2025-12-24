# Field::make() Method Usage Examples

## Overview

The `Field::make()` method provides a clean, fluent way to create form fields using a simple static method.

## Syntax

```php
Field::make(string $type, string $name, array $options = []): Field
```

**Parameters:**
- `$type` - Field type (text, email, select, checkbox, etc.)
- `$name` - Field name attribute
- `$options` - Optional array of field properties

## Basic Examples

### Text Input
```php
use Litepie\Form\Field;

$username = Field::make('text', 'username')
    ->label('Username')
    ->placeholder('Enter username')
    ->required();
```

### Email Input
```php
$email = Field::make('email', 'contact_email')
    ->label('Email Address')
    ->required()
    ->rules('required|email');
```

### Select Dropdown
```php
$theme = Field::make('select', 'theme')
    ->label('Select Theme')
    ->options([
        'dark' => 'Dark Mode',
        'light' => 'Light Mode',
        'auto' => 'Auto'
    ])
    ->value('light');
```

### Checkbox
```php
$terms = Field::make('checkbox', 'accept_terms')
    ->label('I accept the terms and conditions')
    ->required()
    ->rules('accepted');
```

### Textarea
```php
$bio = Field::make('textarea', 'bio')
    ->label('Biography')
    ->placeholder('Tell us about yourself...')
    ->setAttribute('rows', 5)
    ->setAttribute('maxlength', 500);
```

### Avatar Upload
```php
$avatar = Field::make('avatar', 'profile_picture')
    ->label('Profile Picture')
    ->size(150)
    ->circle()
    ->initialsFrom('name')
    ->defaultAvatar('/images/default-avatar.png');
```

## Advanced Field Types

### Currency Field
```php
$price = Field::make('currency', 'product_price')
    ->label('Price')
    ->currency('USD')
    ->required();
```

### Toggle Switch
```php
$notifications = Field::make('toggle', 'email_notifications')
    ->label('Email Notifications')
    ->onLabel('Enabled')
    ->offLabel('Disabled');
```

### Code Editor
```php
$script = Field::make('code', 'custom_script')
    ->label('Custom JavaScript')
    ->language('javascript')
    ->theme('monokai')
    ->height(400);
```

### Markdown Editor
```php
$content = Field::make('markdown', 'article_content')
    ->label('Article Content')
    ->preview(true)
    ->height(500);
```

### Date Time Local
```php
$appointment = Field::make('datetime-local', 'appointment_time')
    ->label('Appointment')
    ->required()
    ->setAttribute('min', now()->format('Y-m-d\TH:i'));
```

### Icon Picker
```php
$icon = Field::make('icon', 'menu_icon')
    ->label('Menu Icon')
    ->library('fontawesome')
    ->searchable(true);
```

### Repeater
```php
$addresses = Field::make('repeater', 'addresses')
    ->label('Addresses')
    ->schema([
        Field::make('text', 'street')->label('Street'),
        Field::make('text', 'city')->label('City'),
        Field::make('text', 'zip')->label('ZIP Code')
    ])
    ->min(1)
    ->max(5);
```

## Using with Advanced Features

### Conditional Visibility
```php
$creditCard = Field::make('text', 'credit_card_number')
    ->label('Credit Card Number')
    ->visibleWhen('payment_method', '=', 'card')
    ->requiredWhen('payment_method', '=', 'card');
```

### Computed Fields
```php
$total = Field::make('text', 'total_price')
    ->label('Total')
    ->computed(fn($data) => ($data['quantity'] ?? 0) * ($data['price'] ?? 0))
    ->readonly();
```

### With Tooltip and Example
```php
$apiKey = Field::make('text', 'api_key')
    ->label('API Key')
    ->tooltip('Found in your account settings')
    ->example('sk_live_4eC39HqLyjWDarjtT1zdp7dc')
    ->required();
```

### With Custom Validation Messages
```php
$workEmail = Field::make('email', 'work_email')
    ->label('Work Email')
    ->rules('required|email|ends_with:@company.com')
    ->validationMessage('required', 'Work email is mandatory')
    ->validationMessage('email', 'Please enter a valid email')
    ->validationMessage('ends_with', 'Must be a company email');
```

### With Column Layout
```php
$firstName = Field::make('text', 'first_name')
    ->label('First Name')
    ->columns(6)
    ->required();

$lastName = Field::make('text', 'last_name')
    ->label('Last Name')
    ->columns(6)
    ->required();
```

## Complete Form Example

```php
use Litepie\Form\Facades\Form;
use Litepie\Form\Field;

$form = Form::create()
    ->action('/profile/update')
    ->method('POST')
    
    // Personal Information
    ->add(Field::make('text', 'first_name')
        ->label('First Name')
        ->columns(6)
        ->required()
    )
    ->add(Field::make('text', 'last_name')
        ->label('Last Name')
        ->columns(6)
        ->required()
    )
    
    // Contact
    ->add(Field::make('email', 'email')
        ->label('Email')
        ->columns(12)
        ->required()
    )
    ->add(Field::make('tel', 'phone')
        ->label('Phone')
        ->columns(6)
        ->placeholder('+1 (555) 123-4567')
    )
    
    // Preferences
    ->add(Field::make('select', 'country')
        ->label('Country')
        ->columns(6)
        ->options([
            'US' => 'United States',
            'CA' => 'Canada',
            'UK' => 'United Kingdom'
        ])
    )
    ->add(Field::make('toggle', 'newsletter')
        ->label('Subscribe to Newsletter')
        ->columns(12)
    )
    
    // Bio
    ->add(Field::make('textarea', 'bio')
        ->label('Biography')
        ->columns(12)
        ->setAttribute('rows', 5)
        ->setAttribute('maxlength', 500)
    )
    
    // Submit
    ->add(Field::make('submit', 'submit')
        ->value('Update Profile')
        ->addClass('btn btn-primary')
    );

return $form;
```

## All Available Field Types

You can use any of these field types with `Field::make()`:

### Basic Input
- `text`, `email`, `password`, `number`, `tel`, `url`, `search`, `hidden`, `autocomplete`

### Date/Time
- `date`, `time`, `datetime`, `datetime-local`, `week`, `month`, `daterange`

### Selection
- `select`, `radio`, `checkbox`, `checkbox_group`, `toggle`, `tags`

### Text Areas & Editors
- `textarea`, `richtext`, `markdown`, `code`, `json`

### Specialized Numeric
- `currency`, `percentage`

### File/Media
- `file`, `image`, `avatar`, `gallery`

### Visual/Interactive
- `color`, `range`, `rating`, `map`, `icon`

### Complex/Dynamic
- `repeater`, `keyvalue`

### Layout & Content
- `divider`, `html`

### Form Controls
- `submit`, `button`, `reset`

## Benefits of Field::make()

1. **Clean Syntax** - Simple, readable field creation
2. **Type Safety** - IDE autocomplete support
3. **Consistency** - Uniform API across all field types
4. **Fluent API** - Chain methods for configuration
5. **Factory Pattern** - Uses the FieldFactory under the hood
6. **Flexibility** - Can pass options array or use fluent methods

## Migration from Old Syntax

### Before (array-based)
```php
$form->add('email', 'email', [
    'label' => 'Email Address',
    'required' => true,
    'placeholder' => 'you@example.com',
    'validation' => 'required|email'
]);
```

### After (Field::make)
```php
$form->add(Field::make('email', 'email')
    ->label('Email Address')
    ->required()
    ->placeholder('you@example.com')
    ->rules('required|email')
);
```

Both methods are supported and work identically!
