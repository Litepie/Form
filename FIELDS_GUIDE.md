# Fields Guide

Complete guide to all field types, creation methods, and API reference for the Litepie Form Builder package.

---

## Table of Contents

1. [Quick Reference](#quick-reference)
2. [Creating Fields](#creating-fields)
3. [Field API Reference](#field-api-reference)

---

## Quick Reference

### Total Field Types: 35

The Litepie Form Builder package includes **35 field types**, making it one of the most comprehensive form builders available for Laravel.

### Field Types by Category

#### 📝 Text Input Fields (9)
1. **TextField** - Standard text input
2. **EmailField** - Email with validation
3. **PasswordField** - Password input (masked)
4. **NumberField** - Numeric input
5. **TelField** - Telephone number
6. **UrlField** - URL with validation
7. **SearchField** - Search input
8. **HiddenField** - Hidden field
9. **AutocompleteField** ✨ NEW - Text with autocomplete suggestions

#### 📅 Date/Time Fields (7)
10. **DateField** - Date picker
11. **TimeField** - Time picker
12. **DateTimeField** - Combined date/time
13. **DateTimeLocalField** ✨ NEW - HTML5 datetime-local
14. **WeekField** - Week picker
15. **MonthField** - Month picker
16. **DateRangeField** - Date range picker

#### ☑️ Selection Fields (6)
17. **SelectField** - Dropdown (single/multi)
18. **RadioField** - Radio buttons
19. **CheckboxField** - Single checkbox
20. **CheckboxGroupField** ✨ NEW - Multiple checkboxes as group
21. **ToggleField** ✨ NEW - On/off switch
22. **TagsField** - Tag input with autocomplete

#### 📄 Text Areas & Editors (2)
23. **TextareaField** - Multi-line text
24. **RichTextField** - WYSIWYG editor

#### 💰 Specialized Numeric Fields (2)
25. **CurrencyField** ✨ NEW - Money with currency symbol
26. **PercentageField** ✨ NEW - Percentage (0-100)

#### 📁 File/Media Fields (2)
27. **FileField** - File upload
28. **ImageField** - Image upload with crop

#### 🎨 Visual/Interactive Fields (3)
29. **ColorField** - Color picker
30. **RangeField** - Slider
31. **RatingField** - Star rating

#### 🔄 Complex/Dynamic Fields (1)
32. **RepeaterField** ✨ NEW - Dynamic array of fields

#### 🔘 Form Control Fields (3)
33. **SubmitField** - Submit button
34. **ButtonField** - Generic button
35. **ResetField** - Reset button

### Quick Examples

#### ToggleField
```php
Field::toggle('notifications')
    ->label('Enable Notifications')
    ->onLabel('Enabled')
    ->offLabel('Disabled');
```

#### CheckboxGroupField
```php
Field::checkboxGroup('permissions')
    ->label('User Permissions')
    ->options([
        'read' => 'Read',
        'write' => 'Write',
        'delete' => 'Delete',
        'admin' => 'Administrator'
    ])
    ->inline(true);
```

#### CurrencyField
```php
Field::currency('price')
    ->label('Product Price')
    ->currency('USD')
    ->min(0)
    ->step(0.01);
```

#### RepeaterField
```php
Field::repeater('addresses')
    ->label('Addresses')
    ->schema([
        Field::text('street')->label('Street'),
        Field::text('city')->label('City'),
        Field::text('zip')->label('ZIP Code')
    ])
    ->min(1)
    ->max(5)
    ->sortable(true);
```

### Comparison with Other Form Builders

| Feature | Litepie Form | Filament | Laravel Nova | FormKit |
|---------|--------------|----------|--------------|---------|
| Total Field Types | **35** | ~35 | ~30 | ~25 |
| Currency Field | ✅ | ✅ | ❌ | ✅ |
| Repeater Field | ✅ | ✅ | ✅ | ✅ |
| Toggle Switch | ✅ | ✅ | ✅ | ✅ |
| Autocomplete | ✅ | ✅ | ✅ | ✅ |

**Litepie Form has more field types than any comparable Laravel form builder!**

---

## Creating Fields

### Overview

The `Field::make()` method provides a clean, fluent way to create form fields using a simple static method.

### Syntax

```php
Field::make(string $type, string $name, array $options = []): Field
```

**Parameters:**
- `$type` - Field type (text, email, select, checkbox, etc.)
- `$name` - Field name attribute
- `$options` - Optional array of field properties

### Basic Examples

#### Text Input
```php
use Litepie\Form\Field;

$username = Field::make('text', 'username')
    ->label('Username')
    ->placeholder('Enter username')
    ->required();
```

#### Email Input
```php
$email = Field::make('email', 'contact_email')
    ->label('Email Address')
    ->required()
    ->rules('required|email');
```

#### Select Dropdown
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

#### Checkbox
```php
$terms = Field::make('checkbox', 'accept_terms')
    ->label('I accept the terms and conditions')
    ->required()
    ->rules('accepted');
```

#### Textarea
```php
$bio = Field::make('textarea', 'bio')
    ->label('Biography')
    ->placeholder('Tell us about yourself...')
    ->setAttribute('rows', 5)
    ->setAttribute('maxlength', 500);
```

### Advanced Field Types

#### Currency Field
```php
$price = Field::make('currency', 'product_price')
    ->label('Price')
    ->currency('USD')
    ->required();
```

#### Toggle Switch
```php
$notifications = Field::make('toggle', 'email_notifications')
    ->label('Email Notifications')
    ->onLabel('Enabled')
    ->offLabel('Disabled');
```

#### Date Time Local
```php
$appointment = Field::make('datetime-local', 'appointment_time')
    ->label('Appointment')
    ->required()
    ->setAttribute('min', now()->format('Y-m-d\TH:i'));
```

#### Repeater
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

### Using with Advanced Features

#### Conditional Visibility
```php
$creditCard = Field::make('text', 'credit_card_number')
    ->label('Credit Card Number')
    ->visibleWhen('payment_method', '=', 'card')
    ->requiredWhen('payment_method', '=', 'card');
```

#### Computed Fields
```php
$total = Field::make('text', 'total_price')
    ->label('Total')
    ->computed(fn($data) => ($data['quantity'] ?? 0) * ($data['price'] ?? 0))
    ->readonly();
```

#### With Tooltip and Example
```php
$apiKey = Field::make('text', 'api_key')
    ->label('API Key')
    ->tooltip('Found in your account settings')
    ->example('sk_live_4eC39HqLyjWDarjtT1zdp7dc')
    ->required();
```

#### With Custom Validation Messages
```php
$workEmail = Field::make('email', 'work_email')
    ->label('Work Email')
    ->rules('required|email|ends_with:@company.com')
    ->validationMessage('required', 'Work email is mandatory')
    ->validationMessage('email', 'Please enter a valid email')
    ->validationMessage('ends_with', 'Must be a company email');
```

#### With Column Layout
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

### Complete Form Example

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

### All Available Field Types

You can use any of these field types with `Field::make()`:

**Basic Input:** `text`, `email`, `password`, `number`, `tel`, `url`, `search`, `hidden`, `autocomplete`

**Date/Time:** `date`, `time`, `datetime`, `datetime-local`, `week`, `month`, `daterange`

**Selection:** `select`, `radio`, `checkbox`, `checkbox_group`, `toggle`, `tags`

**Text Areas & Editors:** `textarea`, `richtext`

**Specialized Numeric:** `currency`, `percentage`

**File/Media:** `file`, `image`

**Visual/Interactive:** `color`, `range`, `rating`

**Complex/Dynamic:** `repeater`

**Form Controls:** `submit`, `button`, `reset`

### Benefits of Field::make()

1. **Clean Syntax** - Simple, readable field creation
2. **Type Safety** - IDE autocomplete support
3. **Consistency** - Uniform API across all field types
4. **Fluent API** - Chain methods for configuration
5. **Factory Pattern** - Uses the FieldFactory under the hood
6. **Flexibility** - Can pass options array or use fluent methods

### Migration from Old Syntax

#### Before (array-based)
```php
$form->add('email', 'email', [
    'label' => 'Email Address',
    'required' => true,
    'placeholder' => 'you@example.com',
    'validation' => 'required|email'
]);
```

#### After (Field::make)
```php
$form->add(Field::make('email', 'email')
    ->label('Email Address')
    ->required()
    ->placeholder('you@example.com')
    ->rules('required|email')
);
```

Both methods are supported and work identically!

---

## Field API Reference

Complete API reference for all field types in the Litepie Form Builder package.

### Base Field API

All field types extend the base `Field` class and inherit these methods:

#### Core Methods

##### `make(string $type, string $name, array $options = []): Field`
Static factory method to create field instances.
```php
Field::make('text', 'username');
```

##### `value(mixed $value): self`
Set the field value.
```php
$field->value('John Doe');
```

##### `getValue(): mixed`
Get the current field value.
```php
$value = $field->getValue();
```

#### Label & Placeholder

##### `label(?string $label = null): string|self`
Get or set the field label.
```php
$field->label('Full Name'); // Set
$label = $field->label();   // Get
```

##### `placeholder(?string $placeholder = null): self|string|null`
Get or set placeholder text.
```php
$field->placeholder('Enter your name...');
```

#### Attributes

##### `attribute(string $key, mixed $value): self`
Set a single HTML attribute.
```php
$field->attribute('data-custom', 'value');
```

##### `attributes(?array $attributes = null): array|self`
Get or set multiple attributes.
```php
$field->attributes(['data-foo' => 'bar', 'data-baz' => 'qux']);
```

##### `addClass(string $class): self`
Add CSS class(es) to the field.
```php
$field->addClass('form-control-lg');
```

#### Validation

##### `required(bool $required = true): self`
Mark field as required.
```php
$field->required();
$field->required(false); // Make optional
```

##### `validation(?string $rules = null): string|self`
Set Laravel validation rules.
```php
$field->validation('required|email|max:255');
```

##### `requiredWhen(string $field, string $operator, mixed $value): self`
Set conditional required rule.
```php
$field->requiredWhen('type', '=', 'premium');
```

##### `validationMessage(string $rule, string $message): self`
Set custom validation message.
```php
$field->validationMessage('required', 'This field is mandatory');
```

#### Help Text & Tooltips

##### `help(?string $help = null): self|string|null`
Set help text displayed below the field.
```php
$field->help('Enter your registered email address');
```

##### `tooltip(?string $tooltip = null): self|string|null`
Set tooltip text (shown on hover).
```php
$field->tooltip('This is a hint');
```

##### `example(?string $example = null): self|string|null`
Set example value or hint.
```php
$field->example('e.g., john@example.com');
```

#### Visibility & Permissions

##### `visible(bool $visible = true): self`
Set field visibility.
```php
$field->visible(false); // Hide field
```

##### `hide(): self`
Hide the field.
```php
$field->hide();
```

##### `show(): self`
Show the field.
```php
$field->show();
```

##### `visibleWhen(...$args): self`
Set conditional visibility (closure or declarative).
```php
// Closure-based
$field->visibleWhen(fn($data) => $data['type'] === 'premium');

// Declarative
$field->visibleWhen('type', '=', 'premium');
$field->visibleWhen('age', '>', 18);
$field->visibleWhen('status', 'in', ['active', 'pending']);
```

**Supported operators:**
- `=`, `==`, `===` - Equality
- `!=`, `!==` - Inequality
- `>`, `>=`, `<`, `<=` - Comparison
- `in`, `not_in` - Array membership
- `contains` - String contains
- `starts_with`, `ends_with` - String position

##### `can(string $permission): self`
Set visibility based on user permission.
```php
$field->can('edit-posts');
```

##### `roles(array|string $roles): self`
Set visibility based on user roles.
```php
$field->roles(['admin', 'editor']);
$field->roles('admin');
```

#### State Management

##### `readonly(bool $readonly = true): self`
Make field read-only.
```php
$field->readonly();
```

##### `disabled(bool $disabled = true): self`
Disable the field.
```php
$field->disabled();
```

#### Layout & Grouping

##### `width(int $columns, int $total = 12): self`
Set field width in grid columns.
```php
$field->width(6, 12); // Half width
$field->width(4, 12); // One-third width
```

##### `col(int $columns): self`
Shorthand for width (assumes 12-column grid).
```php
$field->col(6); // Half width
$field->col(4); // One-third width
```

##### `row(string $row): self`
Set row identifier for grouping.
```php
$field->row('personal_info');
```

##### `group(string $group): self`
Set group identifier.
```php
$field->group('account_details');
```

##### `section(string $section): self`
Set section identifier.
```php
$field->section('billing');
```

#### Advanced Features

##### `dependsOn(string $fieldName): self`
Set field dependency.
```php
$field->dependsOn('country');
```

##### `computed(\Closure $callback): self`
Set computed field callback.
```php
$field->computed(fn($data) => $data['price'] * 1.2); // Add 20% tax
```

##### `loadingText(string $text): self`
Set loading text for async operations.
```php
$field->loadingText('Loading options...');
```

##### `confirmChange(string $message): self`
Set confirmation message for changes.
```php
$field->confirmChange('Are you sure you want to change this?');
```

##### `trackChanges(bool $track = true): self`
Enable change tracking.
```php
$field->trackChanges();
```

##### `columns(int|array $columns): self`
Set number of columns for layout.
```php
$field->columns(2);
```

#### Getters

All setter methods have corresponding getter methods:

```php
$field->getName();        // Get field name
$field->getType();        // Get field type
$field->getId();          // Get field ID
$field->getLabel();       // Get label
$field->getPlaceholder(); // Get placeholder
$field->getHelp();        // Get help text
$field->getTooltip();     // Get tooltip
$field->getExample();     // Get example
$field->getErrors();      // Get validation errors
$field->getAttributes();  // Get all attributes
$field->getOptions();     // Get options array
$field->getRules();       // Get validation rules
$field->getWidth();       // Get width
$field->isRequired();     // Check if required
$field->isReadonly();     // Check if readonly
$field->isDisabled();     // Check if disabled
$field->isVisible();      // Check if visible
$field->hasErrors();      // Check if has errors
```

#### Array Conversion

##### `toArray(): array`
Convert field to array representation.
```php
$array = $field->toArray();
```

---

### Text Input Fields

#### TextField

Standard text input field.

```php
Field::make('text', 'username')
    ->label('Username')
    ->placeholder('Enter username')
    ->validation('required|min:3|max:255')
    ->help('Choose a unique username');
```

**All base field methods apply.**

#### EmailField

Email input with built-in validation.

```php
Field::make('email', 'email')
    ->label('Email Address')
    ->placeholder('user@example.com')
    ->required()
    ->validation('email:rfc,dns');
```

#### PasswordField

Password input field (masked).

```php
Field::make('password', 'password')
    ->label('Password')
    ->required()
    ->validation('min:8|confirmed');
```

#### NumberField

Numeric input field.

```php
Field::make('number', 'age')
    ->label('Age')
    ->attribute('min', 0)
    ->attribute('max', 120)
    ->attribute('step', 1);
```

#### TelField

Telephone number input.

```php
Field::make('tel', 'phone')
    ->label('Phone Number')
    ->placeholder('+1 (555) 123-4567')
    ->validation('required|regex:/^[\+]?[0-9\s\-\(\)]+$/');
```

#### UrlField

URL input with validation.

```php
Field::make('url', 'website')
    ->label('Website')
    ->placeholder('https://example.com')
    ->validation('url');
```

#### SearchField

Search input field.

```php
Field::make('search', 'query')
    ->label('Search')
    ->placeholder('Search...');
```

#### HiddenField

Hidden input field.

```php
Field::make('hidden', 'user_id')
    ->value($userId);
```

#### AutocompleteField

Text input with autocomplete suggestions.

```php
Field::make('autocomplete', 'country')
    ->label('Country')
    ->options(['USA', 'Canada', 'UK', 'Australia', 'Germany'])
    ->attribute('allowCustom', false)
    ->attribute('minLength', 2)
    ->attribute('maxSuggestions', 10);
```

**Additional Attributes:**
- `allowCustom` (bool) - Allow custom values not in options
- `minLength` (int) - Minimum characters before showing suggestions
- `maxSuggestions` (int) - Maximum suggestions to display

---

### Date/Time Fields

#### DateField

Date picker field.

```php
Field::make('date', 'birth_date')
    ->label('Birth Date')
    ->attribute('min', '1900-01-01')
    ->attribute('max', date('Y-m-d'))
    ->required();
```

#### TimeField

Time picker field.

```php
Field::make('time', 'appointment_time')
    ->label('Appointment Time')
    ->attribute('min', '09:00')
    ->attribute('max', '17:00')
    ->attribute('step', 900); // 15 minutes
```

#### DateTimeField

Combined date and time picker.

```php
Field::make('datetime', 'event_start')
    ->label('Event Start')
    ->required();
```

#### DateTimeLocalField

HTML5 datetime-local input.

```php
Field::make('datetime-local', 'appointment')
    ->label('Appointment')
    ->attribute('min', now()->format('Y-m-d\TH:i'))
    ->required();
```

#### WeekField

Week picker field.

```php
Field::make('week', 'work_week')
    ->label('Work Week')
    ->attribute('min', '2024-W01')
    ->attribute('max', '2024-W52');
```

#### MonthField

Month picker field.

```php
Field::make('month', 'billing_month')
    ->label('Billing Month')
    ->attribute('min', '2024-01')
    ->attribute('max', '2024-12');
```

#### DateRangeField

Date range picker field.

```php
Field::make('daterange', 'event_dates')
    ->label('Event Dates')
    ->attribute('format', 'Y-m-d')
    ->attribute('separator', ' to ')
    ->required();
```

---

### Selection Fields

#### SelectField

Dropdown select field (single or multiple).

```php
Field::make('select', 'status')
    ->label('Status')
    ->options([
        'draft' => 'Draft',
        'published' => 'Published',
        'archived' => 'Archived'
    ])
    ->required();

// Multiple select
Field::make('select', 'categories')
    ->label('Categories')
    ->options($categories)
    ->attribute('multiple', true)
    ->attribute('searchable', true);
```

**Additional Attributes:**
- `multiple` (bool) - Allow multiple selections
- `searchable` (bool) - Enable search functionality

#### RadioField

Radio button group.

```php
Field::make('radio', 'gender')
    ->label('Gender')
    ->options([
        'male' => 'Male',
        'female' => 'Female',
        'other' => 'Other'
    ])
    ->attribute('inline', true)
    ->required();
```

**Additional Attributes:**
- `inline` (bool) - Display options inline

#### CheckboxField

Single checkbox or checkbox group.

```php
// Single checkbox
Field::make('checkbox', 'agree')
    ->label('I agree to the terms')
    ->required();

// Checkbox group
Field::make('checkbox', 'permissions')
    ->label('Permissions')
    ->options([
        'read' => 'Read',
        'write' => 'Write',
        'delete' => 'Delete'
    ]);
```

#### CheckboxGroupField

Multiple checkboxes as a group.

```php
Field::make('checkbox_group', 'permissions')
    ->label('User Permissions')
    ->options([
        'read' => 'Read',
        'write' => 'Write',
        'delete' => 'Delete',
        'admin' => 'Administrator'
    ])
    ->attribute('inline', true);
```

#### ToggleField

On/off toggle switch.

```php
Field::make('toggle', 'notifications')
    ->label('Enable Notifications')
    ->attribute('onLabel', 'Enabled')
    ->attribute('offLabel', 'Disabled')
    ->value(true);
```

**Additional Attributes:**
- `onLabel` (string) - Label when toggled on
- `offLabel` (string) - Label when toggled off

#### TagsField

Tag input with autocomplete.

```php
Field::make('tags', 'keywords')
    ->label('Keywords')
    ->options(['Laravel', 'PHP', 'Vue', 'React'])
    ->attribute('allowCustom', true)
    ->attribute('maxTags', 10)
    ->help('Press Enter or comma to add tags');
```

**Additional Attributes:**
- `allowCustom` (bool) - Allow custom tags
- `maxTags` (int) - Maximum number of tags

---

### Text Areas & Editors

#### TextareaField

Multi-line text input.

```php
Field::make('textarea', 'description')
    ->label('Description')
    ->placeholder('Enter description...')
    ->attribute('rows', 5)
    ->attribute('cols', 50)
    ->validation('max:1000');
```

#### RichTextField

WYSIWYG rich text editor.

```php
Field::make('richtext', 'content')
    ->label('Content')
    ->attribute('editor', 'tinymce')
    ->attribute('config', [
        'plugins' => 'link image code',
        'toolbar' => 'undo redo | bold italic | link image'
    ])
    ->attribute('height', 400)
    ->required();
```

**Additional Attributes:**
- `editor` (string) - Editor type: 'tinymce', 'ckeditor', 'quill'
- `config` (array) - Editor configuration
- `height` (int) - Editor height in pixels

---

### Numeric Fields

#### CurrencyField

Currency input with symbol.

```php
Field::make('currency', 'price')
    ->label('Product Price')
    ->attribute('currency', 'USD')
    ->attribute('min', 0)
    ->attribute('step', 0.01)
    ->attribute('symbol', '$')
    ->required();
```

**Additional Attributes:**
- `currency` (string) - Currency code: 'USD', 'EUR', 'GBP', 'JPY'
- `symbol` (string) - Currency symbol
- `position` (string) - Symbol position: 'before', 'after'
- `thousands` (string) - Thousands separator
- `decimal` (string) - Decimal separator

#### PercentageField

Percentage input (0-100).

```php
Field::make('percentage', 'discount')
    ->label('Discount')
    ->attribute('decimals', 2)
    ->attribute('min', 0)
    ->attribute('max', 100)
    ->attribute('step', 0.01);
```

**Additional Attributes:**
- `decimals` (int) - Number of decimal places
- `symbol` (bool) - Show % symbol

---

### File/Media Fields

#### FileField

File upload with drag & drop.

```php
use Litepie\Form\Fields\FileField;

$field = new FileField('document');
$field->label('Upload Document')
    ->accept('.pdf,.doc,.docx')
    ->maxSize(10) // MB
    ->maxFiles(5)
    ->multiple()
    ->uploadUrl('/api/upload')
    ->help('Max 10MB per file');
```

**Methods:**
- `multiple(bool $multiple = true): self` - Allow multiple files
- `accept(string $accept): self` - Accepted file types
- `maxSize(int $maxSize): self` - Max file size in MB
- `maxFiles(int $maxFiles): self` - Max number of files
- `uploadUrl(string $url): self` - Upload endpoint URL

#### ImageField

Image upload with crop and preview.

```php
Field::make('image', 'banner')
    ->label('Banner Image')
    ->accept('.jpg,.jpeg,.png,.gif')
    ->maxSize(5)
    ->attribute('crop', true)
    ->attribute('aspectRatio', '16:9')
    ->attribute('minWidth', 1920)
    ->attribute('minHeight', 1080)
    ->required();
```

**Additional Attributes:**
- `crop` (bool) - Enable image cropping
- `aspectRatio` (string) - Crop aspect ratio
- `minWidth` (int) - Minimum width
- `minHeight` (int) - Minimum height
- `preview` (bool) - Show image preview

---

### Visual/Interactive Fields

#### ColorField

Color picker.

```php
Field::make('color', 'theme_color')
    ->label('Theme Color')
    ->value('#3490dc')
    ->attribute('format', 'hex')
    ->attribute('swatches', ['#ff0000', '#00ff00', '#0000ff']);
```

**Additional Attributes:**
- `format` (string) - Color format: 'hex', 'rgb', 'rgba'
- `swatches` (array) - Preset color swatches

#### RangeField

Slider input.

```php
Field::make('range', 'volume')
    ->label('Volume')
    ->value(50)
    ->attribute('min', 0)
    ->attribute('max', 100)
    ->attribute('step', 1)
    ->attribute('showValue', true);
```

**Additional Attributes:**
- `showValue` (bool) - Display current value

#### RatingField

Star rating input.

```php
Field::make('rating', 'product_rating')
    ->label('Rating')
    ->attribute('max', 5)
    ->attribute('icon', 'star')
    ->attribute('allowHalf', true)
    ->attribute('color', '#fbbf24')
    ->required();
```

**Additional Attributes:**
- `max` (int) - Maximum rating value
- `icon` (string) - Rating icon: 'star', 'heart'
- `allowHalf` (bool) - Allow half ratings
- `color` (string) - Icon color

---

### Complex/Dynamic Fields

#### RepeaterField

Dynamic repeating field groups.

```php
use Litepie\Form\Fields\RepeaterField;

$field = new RepeaterField('addresses');
$field->label('Addresses')
    ->schema([
        Field::make('text', 'street')->label('Street'),
        Field::make('text', 'city')->label('City'),
        Field::make('text', 'zip')->label('ZIP Code'),
        Field::make('select', 'country')->label('Country')->options($countries)
    ])
    ->min(1)
    ->max(5)
    ->sortable(true)
    ->addButtonText('Add Address')
    ->removeButtonText('Remove');
```

**Methods:**
- `schema(array $fields): self` - Array of Field instances
- `min(int $min): self` - Minimum items
- `max(int $max): self` - Maximum items
- `sortable(bool $sortable = true): self` - Enable drag-to-reorder
- `addButtonText(string $text): self` - Customize add button text
- `removeButtonText(string $text): self` - Customize remove button text

**Getters:**
- `getSchema(): array`
- `getMin(): int`
- `getMax(): int`
- `isSortable(): bool`

---

### Form Control Fields

#### SubmitField

Submit button.

```php
Field::make('submit', 'submit')
    ->label('Save Changes')
    ->attribute('class', 'btn btn-primary')
    ->attribute('loadingText', 'Saving...');
```

#### ButtonField

Generic button.

```php
Field::make('button', 'cancel')
    ->label('Cancel')
    ->attribute('type', 'button')
    ->attribute('class', 'btn btn-secondary')
    ->attribute('onclick', 'history.back()');
```

#### ResetField

Reset button.

```php
Field::make('reset', 'reset')
    ->label('Reset Form')
    ->attribute('class', 'btn btn-warning');
```

---

## Complete Usage Examples

### Registration Form

```php
use Litepie\Form\FormBuilder;

$form = FormBuilder::make('register')
    ->fields([
        // Personal Information
        Field::make('text', 'first_name')
            ->label('First Name')
            ->required()
            ->col(6),
        
        Field::make('text', 'last_name')
            ->label('Last Name')
            ->required()
            ->col(6),
        
        Field::make('email', 'email')
            ->label('Email Address')
            ->required()
            ->validation('email:rfc,dns|unique:users'),
        
        Field::make('tel', 'phone')
            ->label('Phone Number')
            ->placeholder('+1 (555) 123-4567')
            ->col(6),
        
        Field::make('date', 'birth_date')
            ->label('Date of Birth')
            ->attribute('max', now()->subYears(18)->format('Y-m-d'))
            ->col(6),
        
        // Account Details
        Field::make('text', 'username')
            ->label('Username')
            ->required()
            ->validation('min:3|max:255|unique:users')
            ->help('Choose a unique username'),
        
        Field::make('password', 'password')
            ->label('Password')
            ->required()
            ->validation('min:8|confirmed')
            ->col(6),
        
        Field::make('password', 'password_confirmation')
            ->label('Confirm Password')
            ->required()
            ->col(6),
        
        // Preferences
        Field::make('toggle', 'newsletter')
            ->label('Subscribe to Newsletter')
            ->value(true),
        
        Field::make('checkbox', 'terms')
            ->label('I agree to the Terms and Conditions')
            ->required(),
        
        // Submit
        Field::make('submit', 'submit')
            ->label('Create Account')
            ->attribute('class', 'btn btn-primary btn-lg')
    ]);
```

### Product Form

```php
$form = FormBuilder::make('product')
    ->fields([
        Field::make('text', 'name')
            ->label('Product Name')
            ->required()
            ->validation('max:255'),
        
        Field::make('text', 'slug')
            ->label('Slug')
            ->required()
            ->validation('unique:products')
            ->help('Auto-generated from name'),
        
        Field::make('select', 'category_id')
            ->label('Category')
            ->options($categories)
            ->required()
            ->col(6),
        
        Field::make('select', 'status')
            ->label('Status')
            ->options([
                'draft' => 'Draft',
                'published' => 'Published',
                'archived' => 'Archived'
            ])
            ->value('draft')
            ->col(6),
        
        Field::make('currency', 'price')
            ->label('Price')
            ->attribute('currency', 'USD')
            ->required()
            ->col(6),
        
        Field::make('percentage', 'discount')
            ->label('Discount')
            ->attribute('max', 50)
            ->col(6),
        
        Field::make('richtext', 'description')
            ->label('Description')
            ->required()
            ->attribute('height', 400),
        
        Field::make('image', 'featured_image')
            ->label('Featured Image')
            ->maxSize(5)
            ->attribute('crop', true)
            ->attribute('aspectRatio', '16:9'),
        
        Field::make('tags', 'tags')
            ->label('Tags')
            ->attribute('allowCustom', true),
        
        Field::make('toggle', 'featured')
            ->label('Featured Product'),
    ]);
```

### User Profile Form with Conditional Fields

```php
$form = FormBuilder::make('profile')
    ->fields([
        Field::make('text', 'name')
            ->label('Full Name')
            ->required(),
        
        Field::make('email', 'email')
            ->label('Email')
            ->required()
            ->readonly(),
        
        Field::make('select', 'account_type')
            ->label('Account Type')
            ->options([
                'personal' => 'Personal',
                'business' => 'Business',
                'organization' => 'Organization'
            ])
            ->required(),
        
        // Show only for business accounts
        Field::make('text', 'company_name')
            ->label('Company Name')
            ->visibleWhen('account_type', '=', 'business')
            ->requiredWhen('account_type', '=', 'business'),
        
        Field::make('text', 'tax_id')
            ->label('Tax ID')
            ->visibleWhen('account_type', '=', 'business')
            ->requiredWhen('account_type', '=', 'business'),
        
        // Show only for organization accounts
        Field::make('text', 'organization_name')
            ->label('Organization Name')
            ->visibleWhen('account_type', '=', 'organization')
            ->requiredWhen('account_type', '=', 'organization'),
        
        Field::make('repeater', 'addresses')
            ->label('Addresses')
            ->schema([
                Field::make('select', 'type')
                    ->label('Type')
                    ->options(['home' => 'Home', 'work' => 'Work', 'other' => 'Other']),
                Field::make('text', 'street')->label('Street'),
                Field::make('text', 'city')->label('City'),
                Field::make('text', 'zip')->label('ZIP'),
                Field::make('autocomplete', 'country')
                    ->label('Country')
                    ->options($countries)
            ])
            ->min(1)
            ->max(3),
    ]);
```

---

## Validation Rules Reference

Common validation rules that can be used with `validation()` method:

### Basic Rules
- `required` - Field must have a value
- `nullable` - Field can be null
- `string` - Must be a string
- `numeric` - Must be numeric
- `integer` - Must be an integer
- `boolean` - Must be boolean

### String Rules
- `min:value` - Minimum length
- `max:value` - Maximum length
- `size:value` - Exact length
- `alpha` - Only alphabetic characters
- `alpha_num` - Only alphanumeric characters
- `alpha_dash` - Alphanumeric with dashes and underscores
- `regex:pattern` - Must match regex pattern

### Numeric Rules
- `between:min,max` - Between two values
- `gt:field` - Greater than another field
- `gte:field` - Greater than or equal
- `lt:field` - Less than another field
- `lte:field` - Less than or equal

### Date Rules
- `date` - Must be a valid date
- `date_format:format` - Must match date format
- `before:date` - Before a date
- `after:date` - After a date
- `before_or_equal:date`
- `after_or_equal:date`

### Email Rules
- `email` - Valid email format
- `email:rfc` - RFC compliant email
- `email:rfc,dns` - RFC compliant with DNS check

### File Rules
- `file` - Must be a file
- `image` - Must be an image (jpeg, png, bmp, gif, svg, webp)
- `mimes:foo,bar` - Must match MIME types
- `mimetypes:text/plain` - Must match MIME types
- `dimensions:min_width=100` - Image dimensions

### Unique & Exists
- `unique:table,column` - Must be unique in database
- `exists:table,column` - Must exist in database

### Confirmation
- `confirmed` - Must have matching `_confirmation` field

### Array Rules
- `array` - Must be an array
- `in:foo,bar` - Must be in list of values
- `not_in:foo,bar` - Must not be in list

---

## Best Practices

### 1. Always Use Labels
```php
// Good
Field::make('text', 'username')->label('Username');

// Avoid
Field::make('text', 'username'); // Uses auto-generated label
```

### 2. Add Help Text for Complex Fields
```php
Field::make('json', 'config')
    ->label('Configuration')
    ->help('Enter valid JSON configuration object');
```

### 3. Use Appropriate Validation
```php
Field::make('email', 'email')
    ->validation('required|email:rfc,dns|unique:users');
```

### 4. Leverage Conditional Visibility
```php
Field::make('text', 'company')
    ->visibleWhen('account_type', '=', 'business')
    ->requiredWhen('account_type', '=', 'business');
```

### 5. Group Related Fields
```php
Field::make('divider')->attribute('text', 'Personal Information'),
Field::make('text', 'first_name')->col(6),
Field::make('text', 'last_name')->col(6),
```

### 6. Use Grid System for Layout
```php
Field::make('text', 'first_name')->col(6),  // Half width
Field::make('text', 'last_name')->col(6),   // Half width
Field::make('email', 'email')->col(12),     // Full width
```

### 7. Set Sensible Defaults
```php
Field::make('select', 'status')
    ->options(['active' => 'Active', 'inactive' => 'Inactive'])
    ->value('active');
```

### 8. Use Placeholders Wisely
```php
Field::make('email', 'email')
    ->placeholder('john@example.com')  // Example format
    ->example('e.g., john@example.com'); // Additional hint
```

---

## Field Types Quick Reference Table

| Type | Class | Primary Use Case |
|------|-------|-----------------|
| text | TextField | Single-line text input |
| email | EmailField | Email addresses |
| password | PasswordField | Password input |
| number | NumberField | Numeric values |
| tel | TelField | Phone numbers |
| url | UrlField | URLs/websites |
| search | SearchField | Search queries |
| hidden | HiddenField | Hidden values |
| autocomplete | AutocompleteField | Text with suggestions |
| date | DateField | Date selection |
| time | TimeField | Time selection |
| datetime | DateTimeField | Date and time |
| datetime-local | DateTimeLocalField | HTML5 datetime |
| week | WeekField | Week selection |
| month | MonthField | Month selection |
| daterange | DateRangeField | Date ranges |
| select | SelectField | Dropdown selection |
| radio | RadioField | Radio buttons |
| checkbox | CheckboxField | Checkboxes |
| checkbox_group | CheckboxGroupField | Checkbox groups |
| toggle | ToggleField | On/off switch |
| tags | TagsField | Tag input |
| textarea | TextareaField | Multi-line text |
| richtext | RichTextField | WYSIWYG editor |
| currency | CurrencyField | Money input |
| percentage | PercentageField | Percentage input |
| file | FileField | File upload |
| image | ImageField | Image upload |
| color | ColorField | Color picker |
| range | RangeField | Slider |
| rating | RatingField | Star rating |
| repeater | RepeaterField | Dynamic fields |
| submit | SubmitField | Submit button |
| button | ButtonField | Generic button |
| reset | ResetField | Reset button |

---

## Support & Resources

- **Main Documentation**: See [readme.md](readme.md) for general package documentation
- **Container Examples**: See [CONTAINER_EXAMPLES.md](CONTAINER_EXAMPLES.md) for form container usage
- **Caching**: See [CACHING.md](CACHING.md) for form caching strategies
- **Advanced Features**: See [ADVANCED_FEATURES.md](ADVANCED_FEATURES.md) for advanced usage
- **New Features**: See [NEW_FEATURES.md](NEW_FEATURES.md) for latest additions

