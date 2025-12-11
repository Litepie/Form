# Advanced Features Guide

This guide demonstrates the advanced features available in the Litepie Form Builder package.

## Field Creation Methods

The package supports multiple ways to create fields:

### Using Field::make() (Recommended)

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

### Using Specific Field Classes

```php
use Litepie\Form\Fields\TextField;
use Litepie\Form\Fields\SelectField;

$textField = new TextField('username');
$selectField = new SelectField('theme');
```

### Using Form Builder

```php
use Litepie\Form\Facades\Form;

$form = Form::create()
    ->add(Field::make('text', 'name'))
    ->add(Field::make('email', 'email'));
```

---

## 1. Conditional Visibility with Operators

Show or hide fields based on the values of other fields using declarative conditions.

### Basic Usage

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
            ->visibleWhen('payment_method', '=', 'card') // Only show when card is selected
    )
    ->add(
        Field::email('paypal_email')
            ->label('PayPal Email')
            ->visibleWhen('payment_method', '=', 'paypal') // Only show when PayPal is selected
    );
```

### Supported Operators

- **Equality**: `=`, `==`, `===`
- **Inequality**: `!=`, `!==`
- **Comparison**: `>`, `>=`, `<`, `<=`
- **Array operations**: `in`, `not_in`
- **String operations**: `contains`, `starts_with`, `ends_with`

### Multiple Conditions

```php
// Field visible only when ALL conditions are true
Field::text('discount_code')
    ->label('VIP Discount Code')
    ->visibleWhen('membership', '=', 'premium')
    ->visibleWhen('total_amount', '>', 100)
    ->visibleWhen('country', 'in', ['US', 'CA', 'UK']);
```

### Closure-based Conditions (Advanced)

```php
Field::text('special_field')
    ->visibleWhen(function($data) {
        return isset($data['age']) && 
               $data['age'] >= 18 && 
               in_array($data['country'], ['US', 'CA']);
    });
```

## 2. Conditional Required Fields

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
            ->requiredWhen('same_as_shipping', '!=', true) // Required when checkbox is unchecked
    )
    ->add(
        Field::select('account_type')
            ->options(['personal' => 'Personal', 'business' => 'Business'])
    )
    ->add(
        Field::text('company_name')
            ->label('Company Name')
            ->requiredWhen('account_type', '=', 'business') // Required for business accounts
    );
```

## 3. Field Dependencies

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

## 4. Computed Fields

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

### Computed Full Name Example

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

## 5. Tooltips and Examples

Provide inline help and example values to guide users.

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
    )
    ->add(
        Field::text('phone')
            ->label('Phone Number')
            ->tooltip('Enter with country code')
            ->example('+1 (555) 123-4567')
    );
```

## 6. Custom Validation Messages

Override default Laravel validation messages per field.

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

## 7. Loading States

Display loading text when field content is being loaded (useful with dependencies).

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

## 8. Confirmation Dialogs

Require user confirmation before changing critical field values.

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

## 9. Change Tracking

Track changes to field values for audit logs or change history.

```php
$form = Form::create()
    ->add(
        Field::text('username')
            ->label('Username')
            ->trackChanges() // Will log old/new values
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

## 10. Column Layouts

Set field width using a 12-column grid system.

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

## Complete Example: E-commerce Checkout Form

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

## Best Practices

1. **Conditional Visibility**: Use declarative `visibleWhen()` for simple conditions, closures for complex logic
2. **Required Fields**: Always pair `visibleWhen()` with `requiredWhen()` for conditional required fields
3. **Dependencies**: Set `loadingText()` for better UX when using `dependsOn()`
4. **Computed Fields**: Always mark computed fields as `readonly()`
5. **Tooltips**: Use tooltips for technical fields that need explanation
6. **Examples**: Provide examples for fields with specific format requirements
7. **Confirmation**: Use `confirmChange()` for destructive or critical actions
8. **Change Tracking**: Enable for fields that require audit trails
9. **Layout**: Use `columns()` for responsive grid layouts instead of manual CSS
10. **Validation Messages**: Provide clear, user-friendly validation messages

## Migration from Basic to Advanced Features

### Before (Basic)
```php
Field::text('company_name')
    ->label('Company Name')
    ->show_if('account_type:business');
```

### After (Advanced)
```php
Field::text('company_name')
    ->label('Company Name')
    ->visibleWhen('account_type', '=', 'business')
    ->requiredWhen('account_type', '=', 'business')
    ->tooltip('Legal company name as registered')
    ->example('Acme Corporation Ltd.')
    ->columns(12);
```

The advanced features provide more control, better UX, and easier integration with modern JavaScript frameworks.
