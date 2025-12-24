# Features Guide

## Overview

The Litepie Form Builder package is a comprehensive form management solution that brings feature parity with modern form builders like Filament, Laravel Nova, and FormKit. This guide covers all basic, advanced, and visibility control features available in the package.

The package provides:
- **Declarative API**: Fluent, chainable methods for building forms
- **Advanced Features**: Conditional visibility, computed fields, field dependencies, and more
- **Visibility Control**: Permission-based and role-based field access
- **Client-Side Ready**: Full JSON/Array export for Vue, React, and other frameworks
- **Performance**: Built-in caching for optimized rendering

---

## Table of Contents

- [Basic Features](#basic-features)
- [New Features](#new-features)
- [Visibility & Permissions](#visibility--permissions)
- [Advanced Usage](#advanced-usage)
- [Best Practices](#best-practices)
- [API Reference](#api-reference)

---

## Basic Features

### Field Creation Methods

The package supports multiple ways to create fields:

#### Using Field::make() (Recommended)

```php
use Litepie\Form\Field;

// Simple field creation
$field = Field::make('text', 'username');

// With options
$field = Field::make('select', 'theme', [
    'label' => 'Theme',
    'options' => ['dark' => 'Dark', 'light' => 'Light']
]);

// Chainable methods
$field = Field::make('email', 'contact_email')
    ->label('Contact Email')
    ->required()
    ->placeholder('you@example.com');
```

#### Using Specific Field Classes

```php
use Litepie\Form\Fields\TextField;
use Litepie\Form\Fields\SelectField;

$textField = new TextField('username');
$selectField = new SelectField('theme');
```

#### Using Form Builder

```php
use Litepie\Form\Facades\Form;

$form = Form::create()
    ->add(Field::make('text', 'name'))
    ->add(Field::make('email', 'email'));
```

### Available Field Types

The package includes a comprehensive set of field types:

- **Text Input**: TextField, EmailField, PasswordField, UrlField, TelField, SearchField
- **Numbers**: NumberField, CurrencyField, PercentageField, RangeField
- **Date/Time**: DateField, TimeField, DateTimeField, DateTimeLocalField, DateRangeField, MonthField, WeekField
- **Selection**: SelectField, RadioField, CheckboxField, CheckboxGroupField, ToggleField
- **Rich Content**: TextareaField, RichTextField
- **Files**: FileField, ImageField
- **Advanced**: AutocompleteField, TagsField, RepeaterField, RatingField
- **Buttons**: SubmitField, ButtonField, ResetField
- **Special**: HiddenField, ColorField

---

## New Features

All requested advanced features have been successfully implemented in the Litepie Form Builder package.

### 1. Conditional Visibility with Operators

**Status**: ✅ Fully Implemented

Show or hide fields based on the values of other fields using declarative conditions.

```php
use Litepie\Form\Facades\Form;
use Litepie\Form\Field;

$form = Form::create()
    ->action('/checkout')
    ->method('POST')
    ->add(
        Field::make('select', 'payment_method')
            ->label('Payment Method')
            ->options([
                'card' => 'Credit Card',
                'paypal' => 'PayPal',
                'bank' => 'Bank Transfer'
            ])
    )
    ->add(
        Field::make('text', 'credit_card')
            ->label('Credit Card Number')
            ->visibleWhen('payment_method', '=', 'card')
    )
    ->add(
        Field::email('paypal_email')
            ->label('PayPal Email')
            ->visibleWhen('payment_method', '=', 'paypal')
    );
```

**Supported Operators**:
- Equality: `=`, `==`, `===`
- Inequality: `!=`, `!==`
- Comparison: `>`, `>=`, `<`, `<=`
- Array: `in`, `not_in`
- String: `contains`, `starts_with`, `ends_with`

**Multiple Conditions**:
```php
// Field visible only when ALL conditions are true
Field::text('discount_code')
    ->label('VIP Discount Code')
    ->visibleWhen('membership', '=', 'premium')
    ->visibleWhen('total_amount', '>', 100)
    ->visibleWhen('country', 'in', ['US', 'CA', 'UK']);
```

**Closure-based Conditions** (for complex logic):
```php
Field::text('special_field')
    ->visibleWhen(function($data) {
        return isset($data['age']) && 
               $data['age'] >= 18 && 
               in_array($data['country'], ['US', 'CA']);
    });
```

---

### 2. Conditional Required Fields

**Status**: ✅ Fully Implemented

Make fields required based on the values of other fields.

```php
$form = Form::create()
    ->add(
        Field::checkbox('same_as_shipping')
            ->label('Billing address same as shipping')
    )
    ->add(
        Field::text('billing_address')
            ->label('Billing Address')
            ->requiredWhen('same_as_shipping', '!=', true)
    )
    ->add(
        Field::select('account_type')
            ->options(['personal' => 'Personal', 'business' => 'Business'])
    )
    ->add(
        Field::text('company_name')
            ->label('Company Name')
            ->requiredWhen('account_type', '=', 'business')
    );
```

---

### 3. Field Dependencies

**Status**: ✅ Fully Implemented

Specify that a field depends on other fields, useful for cascading dropdowns.

```php
$form = Form::create()
    ->add(
        Field::select('country')
            ->label('Country')
            ->options(['US' => 'United States', 'CA' => 'Canada'])
    )
    ->add(
        Field::select('state')
            ->label('State/Province')
            ->dependsOn(['country'])
            ->loadingText('Loading states...')
    )
    ->add(
        Field::select('city')
            ->label('City')
            ->dependsOn(['country', 'state'])
            ->loadingText('Loading cities...')
    );
```

---

### 4. Computed Fields

**Status**: ✅ Fully Implemented

Create fields with automatically calculated values.

```php
$form = Form::create()
    ->add(
        Field::number('quantity')
            ->label('Quantity')
            ->min(1)
    )
    ->add(
        Field::number('unit_price')
            ->label('Unit Price')
    )
    ->add(
        Field::text('total_price')
            ->label('Total Price')
            ->computed(function($data) {
                $qty = $data['quantity'] ?? 0;
                $price = $data['unit_price'] ?? 0;
                return number_format($qty * $price, 2);
            })
            ->readonly()
    );
```

**Computed Full Name Example**:
```php
$form = Form::create()
    ->add(Field::text('first_name')->label('First Name'))
    ->add(Field::text('last_name')->label('Last Name'))
    ->add(
        Field::text('full_name')
            ->label('Full Name')
            ->computed(fn($data) => trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '')))
            ->readonly()
    );
```

---

### 5. Tooltips

**Status**: ✅ Fully Implemented

Provide inline help to guide users.

```php
Field::text('api_key')
    ->tooltip('Found in your account settings under API Access');
```

**With Examples**:
```php
$form = Form::create()
    ->add(
        Field::text('api_key')
            ->label('API Key')
            ->tooltip('Found in your account settings under API Access')
            ->example('sk_live_4eC39HqLyjWDarjtT1zdp7dc')
    )
    ->add(
        Field::url('webhook_url')
            ->label('Webhook URL')
            ->tooltip('HTTPS endpoint that will receive event notifications')
            ->example('https://example.com/webhooks/stripe')
    );
```

---

### 6. Example Values

**Status**: ✅ Fully Implemented

Show example values to guide users on expected format.

```php
Field::text('api_key')
    ->example('sk_live_4eC39HqLyjWDarjtT1zdp7dc');

Field::text('phone')
    ->label('Phone Number')
    ->tooltip('Enter with country code')
    ->example('+1 (555) 123-4567');
```

---

### 7. Custom Validation Messages

**Status**: ✅ Fully Implemented

Override default Laravel validation messages per field.

```php
Field::email('work_email')
    ->rules('required|email')
    ->validationMessage('required', 'Work email is mandatory')
    ->validationMessage('email', 'Please enter a valid work email address');
```

**Multiple Rules**:
```php
$form = Form::create()
    ->add(
        Field::email('work_email')
            ->label('Work Email')
            ->rules('required|email|ends_with:@company.com')
            ->validationMessage('required', 'Work email is mandatory for all employees')
            ->validationMessage('email', 'Please enter a valid email address')
            ->validationMessage('ends_with', 'Must be a company email (@company.com)')
    )
    ->add(
        Field::password('password')
            ->label('Password')
            ->rules('required|min:8|confirmed')
            ->validationMessage('required', 'Password cannot be empty')
            ->validationMessage('min', 'Password must be at least 8 characters')
            ->validationMessage('confirmed', 'Passwords do not match')
    );
```

---

### 8. Loading States

**Status**: ✅ Fully Implemented

Display loading text when field content is being loaded.

```php
Field::select('cities')
    ->dependsOn('country')
    ->loadingText('Loading cities for selected country...');
```

**With Multiple Dependencies**:
```php
$form = Form::create()
    ->add(
        Field::select('brand')
            ->label('Brand')
            ->options(['apple' => 'Apple', 'samsung' => 'Samsung'])
    )
    ->add(
        Field::select('model')
            ->label('Model')
            ->dependsOn(['brand'])
            ->loadingText('Loading available models...')
    )
    ->add(
        Field::select('color')
            ->label('Color')
            ->dependsOn(['brand', 'model'])
            ->loadingText('Loading available colors...')
    );
```

---

### 9. Confirmation Dialogs

**Status**: ✅ Fully Implemented

Require user confirmation before changing critical field values.

```php
Field::checkbox('delete_account')
    ->confirmChange('Are you sure you want to delete your account? This cannot be undone.');
```

**More Examples**:
```php
$form = Form::create()
    ->add(
        Field::checkbox('delete_account')
            ->label('Delete My Account')
            ->confirmChange('Are you sure you want to delete your account? This action cannot be undone.')
    )
    ->add(
        Field::checkbox('cancel_subscription')
            ->label('Cancel Subscription')
            ->confirmChange('Canceling will stop all future billing and remove access. Continue?')
    )
    ->add(
        Field::select('account_status')
            ->label('Account Status')
            ->options(['active' => 'Active', 'suspended' => 'Suspended', 'closed' => 'Closed'])
            ->confirmChange('Changing account status will affect user access. Are you sure?')
    );
```

---

### 10. Change Tracking

**Status**: ✅ Fully Implemented

Track changes to field values for audit logs or change history.

```php
Field::text('username')->trackChanges();
```

**Multiple Fields**:
```php
$form = Form::create()
    ->add(
        Field::text('username')
            ->label('Username')
            ->trackChanges()
    )
    ->add(
        Field::select('account_status')
            ->label('Account Status')
            ->options(['active' => 'Active', 'suspended' => 'Suspended'])
            ->trackChanges()
    )
    ->add(
        Field::number('credit_limit')
            ->label('Credit Limit')
            ->trackChanges()
    );
```

---

### 11. Column Layouts

**Status**: ✅ Fully Implemented

Set field width using a 12-column grid system.

```php
// Two columns (50% each)
Field::text('first_name')->label('First Name')->columns(6);
Field::text('last_name')->label('Last Name')->columns(6);

// Three columns (33% each)
Field::text('city')->label('City')->columns(4);
Field::text('state')->label('State')->columns(4);
Field::text('zip')->label('ZIP')->columns(4);

// Full width
Field::textarea('bio')->label('Biography')->columns(12);

// Mixed widths (25% + 75%)
Field::text('code')->label('Code')->columns(3);
Field::text('description')->label('Description')->columns(9);
```

**Complete Layout Example**:
```php
$form = Form::create()
    // Two columns (50% each)
    ->add(Field::text('first_name')->label('First Name')->columns(6))
    ->add(Field::text('last_name')->label('Last Name')->columns(6))
    
    // Three columns (33% each)
    ->add(Field::text('city')->label('City')->columns(4))
    ->add(Field::text('state')->label('State')->columns(4))
    ->add(Field::text('zip')->label('ZIP')->columns(4))
    
    // Full width
    ->add(Field::textarea('bio')->label('Biography')->columns(12))
    
    // Mixed widths (25% + 75%)
    ->add(Field::text('code')->label('Code')->columns(3))
    ->add(Field::text('description')->label('Description')->columns(9));
```

---

### Properties & Methods Added

**New Properties in Field.php**:
```php
protected ?string $tooltip = null;
protected ?string $example = null;
protected array $dependsOn = [];
protected ?\Closure $computedCallback = null;
protected array $requiredConditions = [];
protected array $validationMessages = [];
protected ?string $loadingText = null;
protected ?string $confirmMessage = null;
protected bool $trackChanges = false;
protected ?int $columns = null;
protected array $visibilityConditions = [];
```

**New Methods in Field.php**:
- `tooltip(?string $tooltip = null): self|string|null`
- `getTooltip(): ?string`
- `example(?string $example = null): self|string|null`
- `getExample(): ?string`
- `dependsOn(array $fields): self`
- `getDependsOn(): array`
- `computed(\Closure $callback): self`
- `isComputed(): bool`
- `computeValue(array $data): mixed`
- `requiredWhen(string $field, string $operator, mixed $value): self`
- `getRequiredConditions(): array`
- `validationMessage(string $rule, string $message): self`
- `getValidationMessages(): array`
- `loadingText(string $text): self`
- `getLoadingText(): ?string`
- `confirmChange(string $message): self`
- `getConfirmMessage(): ?string`
- `trackChanges(): self`
- `isTrackingChanges(): bool`
- `columns(int $columns): self`
- `getColumns(): ?int`
- `visibleWhen(...$args): self` (Enhanced)
- `getVisibilityConditions(): array`
- `meetsVisibilityConditions(array $data): bool`

**Enhanced toArray() Output**:
```php
[
    'name' => $this->name,
    'type' => $this->type,
    'value' => $this->value,
    'label' => $this->getLabel(),
    'placeholder' => $this->placeholder,
    'attributes' => $this->attributes,
    'required' => $this->required,
    'help' => $this->help,
    'tooltip' => $this->tooltip,                        // NEW
    'example' => $this->example,                        // NEW
    'errors' => $this->errors,
    'id' => $this->getId(),
    'dependsOn' => $this->dependsOn,                    // NEW
    'isComputed' => $this->isComputed(),                // NEW
    'requiredConditions' => $this->requiredConditions,  // NEW
    'validationMessages' => $this->validationMessages,  // NEW
    'loadingText' => $this->loadingText,                // NEW
    'confirmMessage' => $this->confirmMessage,          // NEW
    'trackChanges' => $this->trackChanges,              // NEW
    'columns' => $this->columns,                        // NEW
    'visibilityConditions' => $this->visibilityConditions, // NEW
]
```

---

## Visibility & Permissions

The package provides comprehensive field visibility control based on permissions, roles, and custom conditions.

### Basic Visibility

#### Setting User for Visibility Checks

You can set the user once on the form and all subsequent operations will use it:

```php
use Litepie\Form\Facades\Form;

// Set user once
$form = Form::create()
    ->forUser(auth()->user())
    ->add(Form::text('name'))
    ->add(Form::number('salary')->can('view-salary'))
    ->add(Form::select('role')->roles('admin'));

// All these will use the stored user automatically
$html = $form->render();              // Uses auth()->user()
$array = $form->toArray();            // Uses auth()->user()
$json = $form->toJson();              // Uses auth()->user()

// You can still override for specific operations
$adminUser = User::find(1);
$html = $form->render($adminUser);    // Uses $adminUser instead
```

#### Hide/Show Fields

```php
use Litepie\Form\Facades\Form;

$form = Form::create()
    ->add(Form::text('username'))
    ->add(Form::text('internal_id')->hide())  // Hidden field
    ->add(Form::email('email'));

// Toggle visibility
$field = Form::text('status')->visible(false);  // Hidden
$field->visible(true);  // Visible
$field->show();  // Visible
$field->hide();  // Hidden
```

### Permission-Based Visibility

Fields can be shown/hidden based on user permissions using Laravel's authorization system.

```php
$form = Form::create()
    ->add(Form::text('name'))
    ->add(Form::text('email'))
    ->add(
        Form::number('salary')
            ->can('view-salary')  // Only visible if user has 'view-salary' permission
    )
    ->add(
        Form::select('department')
            ->can('manage-departments')
    );

// Render with current user
$html = $form->render(auth()->user());
```

**Multiple Permission Checks**:
```php
// Only visible if user can edit sensitive data
$form->add(
    Form::text('ssn')
        ->label('Social Security Number')
        ->can('edit-sensitive-data')
);

// Only visible if user is admin
$form->add(
    Form::checkbox('is_admin')
        ->label('Administrator')
        ->can('manage-roles')
);
```

### Role-Based Visibility

#### Single Role

```php
$form = Form::create()
    ->add(Form::text('name'))
    ->add(
        Form::textarea('admin_notes')
            ->roles('admin')  // Only visible to admins
    );
```

#### Multiple Roles

```php
// Visible to admins, managers, or supervisors
$form->add(
    Form::number('budget')
        ->roles(['admin', 'manager', 'supervisor'])
);

// Alternative syntax
$form->add(
    Form::select('approval_status')
        ->roles('admin|manager|supervisor')
);
```

### Conditional Visibility with Closures

For complex visibility logic based on user attributes or relationships:

```php
$form = Form::create()
    ->add(Form::text('name'))
    ->add(Form::email('email'))
    ->add(
        Form::text('company_email')
            ->visibleWhen(function ($user) {
                // Only show if user has company email domain
                return $user && str_ends_with($user->email, '@company.com');
            })
    );

// Complex conditions
$form->add(
    Form::number('commission_rate')
        ->visibleWhen(function ($user) {
            return $user 
                && $user->department === 'sales' 
                && $user->experience_years > 2;
        })
);
```

### Record Ownership & Team Membership

Show fields based on who created the record or team relationships:

```php
// In your controller
$post = Post::find($id);

$form = Form::create()
    ->forUser(auth()->user())
    ->add(Form::text('title'))
    ->add(Form::textarea('content'))
    
    // Only visible to the user who created the post
    ->add(
        Form::textarea('private_notes')
            ->label('Private Notes')
            ->visibleWhen(function ($user) use ($post) {
                return $user && $user->id === $post->user_id;
            })
    )
    
    // Visible to owner OR team members
    ->add(
        Form::select('status')
            ->label('Status')
            ->options(['draft' => 'Draft', 'published' => 'Published'])
            ->visibleWhen(function ($user) use ($post) {
                return $user && (
                    $user->id === $post->user_id ||
                    $user->team_id === $post->team_id
                );
            })
    )
    
    // Visible to owner OR team members OR admins
    ->add(
        Form::checkbox('featured')
            ->label('Featured Post')
            ->visibleWhen(function ($user) use ($post) {
                return $user && (
                    $user->id === $post->user_id ||
                    $user->team_id === $post->team_id ||
                    $user->hasRole('admin')
                );
            })
    );

echo $form->render();
```

### Using Relationships

```php
$project = Project::with('team')->find($id);

$form = Form::create()
    ->forUser(auth()->user())
    ->add(Form::text('name'))
    
    // Visible to project owner
    ->add(
        Form::number('budget')
            ->visibleWhen(fn($user) => $user && $user->id === $project->owner_id)
    )
    
    // Visible to team members (using relationship)
    ->add(
        Form::select('priority')
            ->visibleWhen(function ($user) use ($project) {
                return $user && $project->team->members->contains('id', $user->id);
            })
    )
    
    // Visible to team leads only
    ->add(
        Form::textarea('internal_notes')
            ->visibleWhen(function ($user) use ($project) {
                if (!$user) return false;
                
                $member = $project->team->members->firstWhere('id', $user->id);
                return $member && $member->pivot->role === 'lead';
            })
    );
```

### Combining Conditions

```php
// Field visible only if user has permission AND specific role
$form->add(
    Form::text('api_key')
        ->can('access-api')
        ->roles(['developer', 'admin'])
        ->visibleWhen(function ($user) {
            return $user && $user->api_enabled;
        })
);

// All conditions must be met for field to be visible
```

### Field States

#### Readonly Fields

```php
// Make field readonly but visible
$form->add(
    Form::text('created_by')
        ->readonly()  // Field is visible but not editable
);

// Conditional readonly
$form->add(
    Form::text('status')
        ->readonly(auth()->user()->cannot('edit-status'))
);
```

#### Disabled Fields

```php
// Make field disabled
$form->add(
    Form::text('locked_field')
        ->disabled()  // Field is visible but disabled
);

// Conditional disabled
$form->add(
    Form::select('priority')
        ->disabled(!auth()->user()->hasRole('manager'))
);
```

#### Checking States

```php
$field = Form::text('name')->readonly();

if ($field->isReadonly()) {
    // Field is readonly
}

if ($field->isDisabled()) {
    // Field is disabled
}

if ($field->isVisible(auth()->user())) {
    // Field is visible to current user
}
```

### Client-Side Integration

#### Vue.js Example

```php
// Laravel Controller
public function getUserForm()
{
    $form = Form::create()
        ->action('/api/users')
        ->forUser(auth()->user())
        ->add(Form::text('name')->label('Name'))
        ->add(Form::email('email')->label('Email'))
        ->add(
            Form::number('salary')
                ->label('Salary')
                ->can('view-salary')
        );
    
    return response()->json([
        'form' => $form->toArray()
    ]);
}
```

```javascript
// Vue.js Component
export default {
  data() {
    return {
      formData: null,
      formValues: {}
    }
  },
  async mounted() {
    const response = await axios.get('/api/user-form');
    this.formData = response.data.form;
    
    // Initialize form values
    this.formData.fields.forEach(field => {
      this.formValues[field.name] = field.value || '';
    });
  }
}
```

#### React Example

```javascript
// React Component
import { useState, useEffect } from 'react';

function UserForm() {
  const [formData, setFormData] = useState(null);
  
  useEffect(() => {
    fetch('/api/user-form')
      .then(res => res.json())
      .then(data => {
        // Only fields visible to current user are included
        setFormData(data.form);
      });
  }, []);
  
  if (!formData) return <div>Loading...</div>;
  
  return (
    <form action={formData.config.action} method={formData.config.method}>
      {Object.entries(formData.fields).map(([name, field]) => (
        <div key={name} className="form-field">
          <label>{field.label}</label>
          <input
            type={field.type}
            name={field.name}
            value={field.value || ''}
            placeholder={field.placeholder}
            required={field.required}
            disabled={field.disabled}
            readOnly={field.readonly}
          />
        </div>
      ))}
    </form>
  );
}
```

### Performance: Caching Form Output

For forms that don't change frequently, you can enable caching to improve performance:

```php
// Enable caching with default TTL (1 hour)
$form = Form::create()
    ->forUser(auth()->user())
    ->cache()  // Enable caching
    ->add(Form::text('name'))
    ->add(Form::number('salary')->can('view-salary'));

// First call - generates output and caches it
$html1 = $form->render();  // Slow (generates HTML)

// Second call - returns cached output
$html2 = $form->render();  // Fast (from cache)

// Different operations are cached separately
$array = $form->toArray();  // Cached separately
$json = $form->toJson();    // Cached separately
```

#### Custom Cache TTL

```php
// Cache for 30 minutes (1800 seconds)
$form->cache(1800);

// Cache for 5 minutes
$form->cache(300);

// Cache for 24 hours
$form->cache(86400);
```

#### Cache Per User

Cache is automatically scoped per user:

```php
$form = Form::create()
    ->cache()
    ->add(Form::text('name'))
    ->add(Form::number('salary')->can('view-salary'));

// Each user gets their own cached version
$adminHtml = $form->render($adminUser);     // Cached for admin
$managerHtml = $form->render($managerUser);  // Cached for manager
$userHtml = $form->render($regularUser);     // Cached for regular user
```

#### Clear Cache

```php
// Clear all cached outputs
$form->clearCache();

// Disable caching completely
$form->withoutCache();

// Re-enable caching
$form->cache();
```

---

## Advanced Usage

### Complete Example: E-commerce Checkout Form

Here's a comprehensive example combining multiple advanced features:

```php
use Litepie\Form\Facades\Form;

$form = Form::create()
    ->action('/checkout')
    ->method('POST')
    
    // Personal Information
    ->add(Field::text('first_name')->label('First Name')->columns(6)->rules('required'))
    ->add(Field::text('last_name')->label('Last Name')->columns(6)->rules('required'))
    ->add(Field::email('email')->label('Email')->columns(12)->rules('required|email'))
    
    // Account Type
    ->add(
        Field::select('account_type')
            ->label('Account Type')
            ->columns(12)
            ->options(['personal' => 'Personal', 'business' => 'Business'])
    )
    ->add(
        Field::text('company_name')
            ->label('Company Name')
            ->columns(12)
            ->visibleWhen('account_type', '=', 'business')
            ->requiredWhen('account_type', '=', 'business')
    )
    
    // Membership
    ->add(
        Field::select('membership')
            ->label('Membership Level')
            ->columns(12)
            ->options(['standard' => 'Standard', 'premium' => 'Premium', 'vip' => 'VIP'])
    )
    ->add(
        Field::text('discount_code')
            ->label('VIP Discount Code')
            ->columns(12)
            ->visibleWhen('membership', '=', 'vip')
            ->tooltip('VIP members get exclusive discount codes')
            ->example('VIP2024-SAVE20')
    )
    
    // Payment
    ->add(
        Field::select('payment_method')
            ->label('Payment Method')
            ->columns(12)
            ->options(['card' => 'Credit Card', 'paypal' => 'PayPal', 'bank' => 'Bank Transfer'])
    )
    ->add(
        Field::text('credit_card')
            ->label('Credit Card Number')
            ->columns(8)
            ->visibleWhen('payment_method', '=', 'card')
            ->requiredWhen('payment_method', '=', 'card')
            ->example('4532 1234 5678 9010')
    )
    ->add(
        Field::text('cvv')
            ->label('CVV')
            ->columns(4)
            ->visibleWhen('payment_method', '=', 'card')
            ->requiredWhen('payment_method', '=', 'card')
            ->tooltip('3-digit security code on the back of your card')
    )
    ->add(
        Field::email('paypal_email')
            ->label('PayPal Email')
            ->columns(12)
            ->visibleWhen('payment_method', '=', 'paypal')
            ->requiredWhen('payment_method', '=', 'paypal')
    )
    
    // Pricing
    ->add(Field::number('quantity')->label('Quantity')->columns(6)->min(1)->value(1))
    ->add(Field::hidden('unit_price')->value(99.99))
    ->add(
        Field::text('total')
            ->label('Total Price')
            ->columns(6)
            ->computed(function($data) {
                $qty = $data['quantity'] ?? 1;
                $price = $data['unit_price'] ?? 0;
                return '$' . number_format($qty * $price, 2);
            })
            ->readonly()
    )
    
    // Terms
    ->add(
        Field::checkbox('accept_terms')
            ->label('I accept the terms and conditions')
            ->columns(12)
            ->rules('required|accepted')
            ->confirmChange('Please read the terms carefully before accepting')
            ->trackChanges()
    );

return $form;
```

### Dynamic Form Based on User Role

```php
$form = Form::create()
    ->action('/users')
    ->method('POST');

// Basic fields - visible to all
$form->add(Form::text('name')->label('Full Name'))
    ->add(Form::email('email')->label('Email Address'));

// Manager-only fields
$form->add(
    Form::select('department')
        ->label('Department')
        ->options(['sales' => 'Sales', 'marketing' => 'Marketing'])
        ->roles(['manager', 'admin'])
);

// Admin-only fields
$form->add(
    Form::checkbox('is_active')
        ->label('Active Status')
        ->roles('admin')
);

$form->add(
    Form::number('salary')
        ->label('Annual Salary')
        ->can('manage-payroll')
        ->roles('admin')
);

// Render based on current user
echo $form->render(auth()->user());
```

### Sensitive Data Protection

```php
$form = Form::create()
    ->add(Form::text('name'))
    ->add(Form::email('email'))
    
    // PII - only visible with specific permission
    ->add(
        Form::text('ssn')
            ->label('SSN')
            ->can('view-pii')
            ->readonly()  // Even if visible, can't edit
    )
    
    // Financial data
    ->add(
        Form::number('bank_account')
            ->label('Bank Account')
            ->can('view-financial')
            ->readonly()
    )
    
    // Admin controls
    ->add(
        Form::checkbox('verified')
            ->label('Verified Account')
            ->can('verify-users')
            ->roles('admin')
    );

$html = $form->render(auth()->user());
```

### Progressive Disclosure

```php
$user = auth()->user();

$form = Form::create()
    // Everyone sees these
    ->add(Form::text('name'))
    ->add(Form::email('email'))
    
    // Level 1: Basic users with 'edit-profile' permission
    ->add(
        Form::text('phone')
            ->can('edit-profile')
    )
    
    // Level 2: Users with specific role
    ->add(
        Form::select('team')
            ->roles(['team-lead', 'manager', 'admin'])
    )
    
    // Level 3: Managers and above
    ->add(
        Form::textarea('performance_notes')
            ->roles(['manager', 'admin'])
            ->can('manage-team')
    )
    
    // Level 4: Admin only
    ->add(
        Form::checkbox('system_admin')
            ->roles('admin')
            ->can('manage-system')
    );

echo $form->render($user);
```

### Multi-Tenant Forms

```php
$user = auth()->user();

$form = Form::create()
    ->add(Form::text('name'))
    
    // Only visible to users in specific tenant
    ->add(
        Form::select('region')
            ->visibleWhen(function ($user) {
                return $user && $user->tenant_id === 1;
            })
    )
    
    // Visible to premium tier only
    ->add(
        Form::text('custom_domain')
            ->visibleWhen(function ($user) {
                return $user && $user->tenant && $user->tenant->tier === 'premium';
            })
    )
    
    // Super admin across all tenants
    ->add(
        Form::checkbox('cross_tenant_access')
            ->roles('super-admin')
            ->can('manage-all-tenants')
    );

echo $form->render($user);
```

---

## Best Practices

### Conditional Visibility
1. Use declarative `visibleWhen()` for simple conditions
2. Use closures for complex business logic
3. Always pair `visibleWhen()` with `requiredWhen()` for conditional required fields

### Dependencies
1. Set `loadingText()` for better UX when using `dependsOn()`
2. Keep dependency chains reasonable (avoid too many levels)
3. Consider caching dependent options when possible

### Computed Fields
1. Always mark computed fields as `readonly()`
2. Handle missing data gracefully with null coalescing
3. Keep computation logic simple and fast

### Tooltips & Examples
1. Use tooltips for technical fields needing explanation
2. Provide examples for fields with specific format requirements
3. Keep tooltip text concise and actionable

### Validation
1. Provide clear, user-friendly validation messages
2. Override default messages for better UX
3. Test validation with edge cases

### Confirmation
1. Use `confirmChange()` for destructive or critical actions
2. Make confirmation messages clear and specific
3. Avoid overusing confirmations (only for critical changes)

### Change Tracking
1. Enable for fields requiring audit trails
2. Consider storage implications for high-traffic forms
3. Document what changes are tracked and why

### Layout
1. Use `columns()` for responsive grid layouts
2. Test layouts on different screen sizes
3. Keep related fields together visually

### Permissions & Visibility
1. **Combine Security Layers**: Use both permission and role checks for sensitive fields
2. **Default to Hidden**: For sensitive data, default to hidden and explicitly show when authorized
3. **Client-Side Sync**: Remember that visibility is server-side; ensure client-side forms match
4. **Performance**: Cache permission checks when rendering multiple forms
5. **Testing**: Always test forms with different user roles and permissions

### Caching
**Good for caching:**
- Forms with many fields and complex visibility logic
- API endpoints serving the same form to many users
- Admin panels with heavy permission checks
- Multi-step forms with complex validation

**Not recommended for caching:**
- Forms with dynamic data that changes frequently
- Forms with real-time data (current timestamps, etc.)
- Simple forms with few fields

---

## API Reference

### Field Methods

#### Basic Methods
```php
// Field creation
Field::make(string $type, string $name, array $options = []): Field

// Labels and placeholders
->label(string $label): self
->placeholder(string $placeholder): self
->help(string $help): self

// Values
->value(mixed $value): self
->default(mixed $default): self

// Validation
->required(bool $required = true): self
->rules(string|array $rules): self
->validationMessage(string $rule, string $message): self
->getValidationMessages(): array

// States
->readonly(bool $readonly = true): self
->disabled(bool $disabled = true): self
->hide(): self
->show(): self
->visible(bool $visible = true): self
```

#### Advanced Features
```php
// Conditional visibility
->visibleWhen(string $field, string $operator, mixed $value): self
->visibleWhen(Closure $callback): self
->getVisibilityConditions(): array
->meetsVisibilityConditions(array $data): bool

// Conditional required
->requiredWhen(string $field, string $operator, mixed $value): self
->getRequiredConditions(): array

// Dependencies
->dependsOn(array $fields): self
->getDependsOn(): array

// Computed fields
->computed(Closure $callback): self
->isComputed(): bool
->computeValue(array $data): mixed

// UI enhancements
->tooltip(string $tooltip): self
->getTooltip(): ?string
->example(string $example): self
->getExample(): ?string
->loadingText(string $text): self
->getLoadingText(): ?string

// User interactions
->confirmChange(string $message): self
->getConfirmMessage(): ?string

// Tracking
->trackChanges(): self
->isTrackingChanges(): bool

// Layout
->columns(int $columns): self
->getColumns(): ?int

// Visibility checks
->isVisible(?object $user): bool
->isReadonly(): bool
->isDisabled(): bool
```

#### Permission & Role Methods
```php
// Permission-based visibility
->can(string $permission): self

// Role-based visibility
->roles(array|string $roles): self
```

### Form Methods

```php
// Form creation
Form::create(): FormBuilder

// Configuration
->action(string $action): self
->method(string $method): self
->enctype(string $enctype): self

// User management
->forUser(?object $user): self
->getUser(): ?object

// Field management
->add(Field $field): self
->fields(): Collection
->visibleFields(?object $user): Collection
->getData(): array

// Caching
->cache(int $ttl = 3600): self
->withoutCache(): self
->clearCache(): self

// Rendering
->render(?object $user = null): string
->toArray(?object $user = null): array
->toJson(?object $user = null, int $options = 0): string
```

---

## Comparison with Other Form Builders

| Feature | Litepie Form | Filament | Laravel Nova | FormKit |
|---------|--------------|----------|--------------|---------|
| Conditional Visibility | ✅ | ✅ | ✅ | ✅ |
| Conditional Required | ✅ | ✅ | ✅ | ✅ |
| Field Dependencies | ✅ | ✅ | ✅ | ✅ |
| Computed Fields | ✅ | ✅ | ✅ | ✅ |
| Tooltips | ✅ | ✅ | ✅ | ✅ |
| Examples | ✅ | ✅ | ✅ | ✅ |
| Custom Messages | ✅ | ✅ | ✅ | ✅ |
| Loading States | ✅ | ✅ | ✅ | ✅ |
| Confirmation Dialogs | ✅ | ✅ | ✅ | ✅ |
| Change Tracking | ✅ | ✅ | ✅ | ❌ |
| Column Layouts | ✅ | ✅ | ✅ | ✅ |
| Permission Control | ✅ | ✅ | ✅ | ❌ |
| Role-Based Access | ✅ | ✅ | ✅ | ❌ |
| Output Caching | ✅ | ❌ | ❌ | ❌ |

**Litepie Form is now feature-complete with industry-leading form builders!**

---

## Backward Compatibility

✅ **All existing functionality preserved**:
- Old `visibleWhen(\Closure)` syntax still works
- All existing methods unchanged
- No breaking changes to API

---

## Migration Guide

### From Basic to Advanced Features

**Before (Basic)**:
```php
Field::text('company_name')
    ->label('Company Name')
    ->show_if('account_type:business');
```

**After (Advanced)**:
```php
Field::text('company_name')
    ->label('Company Name')
    ->visibleWhen('account_type', '=', 'business')
    ->requiredWhen('account_type', '=', 'business')
    ->tooltip('Legal company name as registered')
    ->example('Acme Corporation Ltd.')
    ->columns(12);
```

---

## Frontend Integration

All advanced features are included in the `toArray()` output:

```php
$formData = $form->toArray();
// Returns JSON with all field metadata including:
// - visibilityConditions
// - requiredConditions
// - dependsOn
// - isComputed
// - tooltip
// - example
// - validationMessages
// - loadingText
// - confirmMessage
// - trackChanges
// - columns
```

Use this data in your JavaScript framework (Vue, React, Alpine) to implement client-side conditional logic, computed fields, and UI enhancements.
