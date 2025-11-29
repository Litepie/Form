# Field Visibility & Permission Control

This document explains how to control field visibility based on permissions, roles, and custom conditions in the Litepie Form package.

## Table of Contents

- [Basic Visibility](#basic-visibility)
- [Permission-Based Visibility](#permission-based-visibility)
- [Role-Based Visibility](#role-based-visibility)
- [Conditional Visibility](#conditional-visibility)
- [Field States](#field-states)
- [Rendering with Visibility](#rendering-with-visibility)
- [Advanced Examples](#advanced-examples)

## Basic Visibility

### Setting User for Visibility Checks

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

### Hide/Show Fields

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

## Permission-Based Visibility

### Using Laravel Permissions

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

### Multiple Permission Checks

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

## Role-Based Visibility

### Single Role

```php
$form = Form::create()
    ->add(Form::text('name'))
    ->add(
        Form::textarea('admin_notes')
            ->roles('admin')  // Only visible to admins
    );
```

### Multiple Roles

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

## Conditional Visibility

### Custom Logic with Closures

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

### Multiple Record Checks

```php
$document = Document::find($id);
$organization = $document->organization;

$form = Form::create()
    ->forUser(auth()->user())
    ->add(Form::text('title'))
    
    // Check ownership at multiple levels
    ->add(
        Form::file('confidential_attachment')
            ->visibleWhen(function ($user) use ($document, $organization) {
                if (!$user) return false;
                
                // Document owner
                if ($user->id === $document->created_by) {
                    return true;
                }
                
                // Organization admin
                if ($organization->admins->contains('id', $user->id)) {
                    return true;
                }
                
                // Department head
                if ($user->department_id === $document->department_id 
                    && $user->is_department_head) {
                    return true;
                }
                
                return false;
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

## Field States

### Readonly Fields

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

### Disabled Fields

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

### Checking States

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

## Rendering with Visibility

### Setting User Once

The recommended approach is to set the user once using `forUser()`:

```php
$form = Form::create()
    ->forUser(auth()->user())  // Set user once
    ->add(Form::text('name'))
    ->add(Form::number('salary')->can('view-salary'));

// All operations use the stored user
$html = $form->render();
$array = $form->toArray();
$json = $form->toJson();
```

### Server-Side Rendering (HTML)

```php
// Without user - all fields visible
$html = $form->render();

// With user - only visible fields shown
$html = $form->render(auth()->user());

// With specific user
$user = User::find(1);
$html = $form->render($user);
```

### Client-Side (JSON/Array for Vue, React, etc.)

The visibility system also works with `toArray()` and `toJson()` for client-side frameworks:

```php
// Without user - all fields included
$formData = $form->toArray();

// With user - only visible fields included
$formData = $form->toArray(auth()->user());

// Send to client-side framework
return response()->json([
    'form' => $form->toArray(auth()->user())
]);
```

### API Endpoints

```php
// In your controller
public function getForm(Request $request)
{
    $form = Form::create()
        ->forUser(auth()->user())  // Set user once
        ->add(Form::text('name'))
        ->add(Form::email('email'))
        ->add(
            Form::number('salary')
                ->can('view-salary')
        )
        ->add(
            Form::select('department')
                ->roles('manager')
        );
    
    // toArray() automatically uses the stored user
    return response()->json([
        'form' => $form->toArray(),
        'status' => 'success'
    ]);
}
```

### Vue.js Example

```php
// Laravel Controller
public function getUserForm()
{
    $form = Form::create()
        ->action('/api/users')
        ->forUser(auth()->user())  // Set user once
        ->add(Form::text('name')->label('Name'))
        ->add(Form::email('email')->label('Email'))
        ->add(
            Form::number('salary')
                ->label('Salary')
                ->can('view-salary')
        );
    
    // toArray() uses stored user automatically
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

### React Example

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

### Getting Visible Fields

```php
// Get all fields
$allFields = $form->fields();

// Get only visible fields for current user
$visibleFields = $form->visibleFields(auth()->user());

// Count visible fields
$count = $form->visibleFields(auth()->user())->count();
```

### Manual Field Filtering

```php
$user = auth()->user();

foreach ($form->visibleFields($user) as $field) {
    if ($field->isVisible($user)) {
        echo $field->render();
    }
}
```

## Advanced Examples

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

### Conditional Fields Based on Data

```php
$form = Form::create()
    ->add(Form::text('name'))
    ->add(Form::email('email'))
    ->add(
        Form::select('employee_type')
            ->options([
                'full-time' => 'Full Time',
                'part-time' => 'Part Time',
                'contractor' => 'Contractor'
            ])
    )
    ->add(
        Form::text('contract_company')
            ->label('Contracting Company')
            ->visibleWhen(function ($user) {
                // Only show if user is contractor
                return $user && $user->employee_type === 'contractor';
            })
    )
    ->add(
        Form::number('hourly_rate')
            ->visibleWhen(function ($user) {
                return $user && in_array($user->employee_type, ['part-time', 'contractor']);
            })
    );
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

## Best Practices

1. **Combine Security Layers**: Use both permission and role checks for sensitive fields
2. **Default to Hidden**: For sensitive data, default to hidden and explicitly show when authorized
3. **Client-Side Sync**: Remember that visibility is server-side; ensure client-side forms match
4. **Performance**: Cache permission checks when rendering multiple forms
5. **Testing**: Always test forms with different user roles and permissions
6. **Documentation**: Document which permissions/roles are required for each field

## API Reference

### Field Methods

```php
// Basic visibility
$field->hide();                          // Hide field
$field->show();                          // Show field
$field->visible(bool $visible = true);   // Set visibility

// Permission-based
$field->can(string $permission);         // Require permission

// Role-based
$field->roles(array|string $roles);      // Require role(s)

// Conditional
$field->visibleWhen(Closure $callback);  // Custom logic

// States
$field->readonly(bool $readonly = true); // Make readonly
$field->disabled(bool $disabled = true); // Make disabled

// Checks
$field->isVisible(?object $user): bool;  // Check if visible to user
$field->isReadonly(): bool;              // Check if readonly
$field->isDisabled(): bool;              // Check if disabled
```

### Form Methods

```php
// Set user for visibility
$form->forUser(?object $user): self;            // Set user for all operations
$form->getUser(): ?object;                      // Get current user

// Caching
$form->cache(int $ttl = 3600): self;            // Enable caching with TTL in seconds
$form->withoutCache(): self;                    // Disable caching
$form->clearCache(): self;                      // Clear all cached outputs

// Get fields
$form->fields(): Collection;                    // All fields
$form->visibleFields(?object $user): Collection; // Visible fields only
$form->getData(): array;                        // Get form data

// Render
$form->render(?object $user = null): string;    // Render HTML with optional user

// Client-side
$form->toArray(?object $user = null): array;    // Convert to array with optional user
$form->toJson(?object $user = null, int $options = 0): string; // Convert to JSON
```

## Performance: Caching Form Output

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

### Custom Cache TTL

```php
// Cache for 30 minutes (1800 seconds)
$form->cache(1800);

// Cache for 5 minutes
$form->cache(300);

// Cache for 24 hours
$form->cache(86400);
```

### Cache Per User

Cache is automatically scoped per user:

```php
$form = Form::create()
    ->cache()
    ->add(Form::text('name'))
    ->add(Form::number('salary')->can('view-salary'));

// Each user gets their own cached version
$adminHtml = $form->render($adminUser);  // Cached for admin
$managerHtml = $form->render($managerUser);  // Cached for manager
$userHtml = $form->render($regularUser);  // Cached for regular user
```

### Clear Cache

```php
// Clear all cached outputs
$form->clearCache();

// Disable caching completely
$form->withoutCache();

// Re-enable caching
$form->cache();
```

### Use Cases for Caching

**Good for caching:**
- Forms with many fields and complex visibility logic
- API endpoints that serve the same form to many users
- Admin panels with heavy permission checks
- Multi-step forms with complex validation

**Not recommended for caching:**
- Forms with dynamic data that changes frequently
- Forms with real-time data (current timestamps, etc.)
- Simple forms with few fields

```php
// Example: API endpoint with caching
public function getUserForm()
{
    $form = Form::create()
        ->forUser(auth()->user())
        ->cache(1800)  // Cache for 30 minutes
        ->add(Form::text('name'))
        ->add(Form::email('email'))
        ->add(Form::number('salary')->can('view-salary'))
        ->add(Form::select('department')->roles('manager'));
    
    // First request: generates and caches
    // Subsequent requests: returns cached version
    return response()->json([
        'form' => $form->toArray()
    ]);
}
```

// Get fields
$form->fields(): Collection;                    // All fields
$form->visibleFields(?object $user): Collection; // Visible fields only
$form->getData(): array;                        // Get form data

// Render
$form->render(?object $user = null): string;    // Render HTML with optional user

// Client-side
$form->toArray(?object $user = null): array;    // Convert to array with optional user
$form->toJson(?object $user = null, int $options = 0): string; // Convert to JSON
```
