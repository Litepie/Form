# Client-Side Framework Integration

This document shows how to use Litepie Form with client-side frameworks like Vue.js, React, Angular, and others by converting forms to arrays and JSON.

## 🔐 Field Visibility & Permissions

Forms support visibility control based on user permissions, roles, and custom conditions. This works seamlessly with `toArray()` and `toJson()`:

### Setting User Once (Recommended)

```php
use Litepie\Form\Facades\Form;

// Set user once and all operations will use it
$form = Form::create()
    ->action('/api/users')
    ->method('POST')
    ->forUser(auth()->user())  // Set user once here
    
    // Visible to everyone
    ->add(Form::text('name')->label('Name'))
    ->add(Form::email('email')->label('Email'))
    
    // Only visible to users with 'view-salary' permission
    ->add(
        Form::number('salary')
            ->label('Salary')
            ->can('view-salary')
    )
    
    // Only visible to managers and admins
    ->add(
        Form::select('department')
            ->label('Department')
            ->options(['sales' => 'Sales', 'engineering' => 'Engineering'])
            ->roles(['manager', 'admin'])
    );

// All operations automatically use the stored user
$html = $form->render();          // Uses auth()->user()
$array = $form->toArray();        // Uses auth()->user()
$json = $form->toJson();          // Uses auth()->user()

// Return to client-side framework
return response()->json([
    'form' => $form->toArray()    // Already filtered by user
]);
```

### Passing User Per Operation

You can also pass the user to individual operations:

```php
$form = Form::create()
    ->add(Form::text('name'))
    ->add(Form::number('salary')->can('view-salary'));

// Pass user to specific operations
$array = $form->toArray(auth()->user());
$json = $form->toJson(auth()->user());
$html = $form->render(auth()->user());

// Override stored user
$adminUser = User::find(1);
$adminArray = $form->toArray($adminUser);  // Uses admin user instead
```

**Important:** When passing a user to `toArray()` or `toJson()`, only fields that pass the visibility checks will be included in the output. This ensures your client-side application only receives fields the user is authorized to see.

For more details on visibility controls, see [VISIBILITY.md](../VISIBILITY.md).

## 🔧 Converting Forms to Arrays/JSON

### Basic Form to Array

```php
use Litepie\Form\Facades\Form;

// Create form and convert to array
$contactForm = Form::create()
    ->action('/api/contact')
    ->method('POST')
    ->add('name', 'text', [
        'label' => 'Full Name',
        'required' => true,
        'placeholder' => 'Enter your name'
    ])
    ->add('email', 'email', [
        'label' => 'Email',
        'required' => true,
        'validation' => 'required|email'
    ])
    ->add('message', 'textarea', [
        'label' => 'Message',
        'required' => true,
        'rows' => 5
    ]);

// Convert to array
$formArray = $contactForm->toArray();

// Convert to JSON
$formJson = $contactForm->toJson();

// Or use helper functions
$formArray = form_array([
    'name' => ['type' => 'text', 'label' => 'Name', 'required' => true],
    'email' => ['type' => 'email', 'label' => 'Email', 'required' => true],
    'message' => ['type' => 'textarea', 'label' => 'Message', 'required' => true]
], [
    'action' => '/api/contact',
    'method' => 'POST'
]);
```

### Form Array Structure

The `toArray()` method returns a comprehensive structure:

```json
{
  "config": {
    "action": "/api/contact",
    "method": "POST",
    "enctype": "application/x-www-form-urlencoded",
    "csrf": true,
    "ajax": false,
    "theme": "bootstrap5",
    "multiStep": false,
    "attributes": {}
  },
  "fields": {
    "name": {
      "name": "name",
      "type": "text",
      "label": "Full Name",
      "value": null,
      "placeholder": "Enter your name",
      "help": null,
      "required": true,
      "disabled": false,
      "readonly": false,
      "attributes": {},
      "validation": ["required", "string", "max:255"],
      "options": [],
      "class": "form-control",
      "id": "name",
      "step": 1,
      "conditional": {
        "show_if": null,
        "hide_if": null
      },
      "meta": {
        "hasErrors": false,
        "errors": []
      }
    }
  },
  "validation": {
    "rules": {
      "name": ["required", "string", "max:255"],
      "email": ["required", "email"],
      "message": ["required", "string", "min:10"]
    },
    "messages": {}
  },
  "data": {},
  "meta": {
    "fieldCount": 3,
    "requiredFields": ["name", "email", "message"],
    "hasFileUploads": false,
    "steps": []
  }
}
```

## 🚀 Vue.js Integration

### Laravel Controller

```php
class FormController extends Controller
{
    public function getContactForm()
    {
        $form = Form::create()
            ->action('/api/contact')
            ->method('POST')
            ->add('name', 'text', [
                'label' => 'Full Name',
                'required' => true,
                'validation' => 'required|string|max:255'
            ])
            ->add('email', 'email', [
                'label' => 'Email Address',
                'required' => true,
                'validation' => 'required|email'
            ])
            ->add('phone', 'tel', [
                'label' => 'Phone Number',
                'placeholder' => '+1 (555) 123-4567'
            ])
            ->add('subject', 'select', [
                'label' => 'Subject',
                'required' => true,
                'options' => [
                    'general' => 'General Inquiry',
                    'support' => 'Technical Support',
                    'sales' => 'Sales Question'
                ]
            ])
            ->add('message', 'textarea', [
                'label' => 'Message',
                'required' => true,
                'validation' => 'required|string|min:10',
                'rows' => 5
            ])
            ->add('newsletter', 'checkbox', [
                'label' => 'Subscribe to newsletter'
            ]);

        return response()->json($form->toArray());
    }
}
```

### Vue.js Component

```vue
<template>
  <form @submit.prevent="submitForm" :action="formConfig.action" :method="formConfig.method">
    <div v-for="(field, name) in formFields" :key="name" class="mb-3">
      <!-- Text Input -->
      <div v-if="field.type === 'text'" class="form-group">
        <label :for="field.id" class="form-label">
          {{ field.label }}
          <span v-if="field.required" class="text-danger">*</span>
        </label>
        <input
          :id="field.id"
          v-model="formData[name]"
          type="text"
          :class="[field.class, { 'is-invalid': hasFieldError(name) }]"
          :placeholder="field.placeholder"
          :required="field.required"
          :disabled="field.disabled"
          :readonly="field.readonly"
        />
        <div v-if="hasFieldError(name)" class="invalid-feedback">
          {{ getFieldError(name) }}
        </div>
        <small v-if="field.help" class="form-text text-muted">{{ field.help }}</small>
      </div>

      <!-- Email Input -->
      <div v-if="field.type === 'email'" class="form-group">
        <label :for="field.id" class="form-label">
          {{ field.label }}
          <span v-if="field.required" class="text-danger">*</span>
        </label>
        <input
          :id="field.id"
          v-model="formData[name]"
          type="email"
          :class="[field.class, { 'is-invalid': hasFieldError(name) }]"
          :placeholder="field.placeholder"
          :required="field.required"
        />
        <div v-if="hasFieldError(name)" class="invalid-feedback">
          {{ getFieldError(name) }}
        </div>
      </div>

      <!-- Select Dropdown -->
      <div v-if="field.type === 'select'" class="form-group">
        <label :for="field.id" class="form-label">
          {{ field.label }}
          <span v-if="field.required" class="text-danger">*</span>
        </label>
        <select
          :id="field.id"
          v-model="formData[name]"
          :class="[field.class, { 'is-invalid': hasFieldError(name) }]"
          :required="field.required"
        >
          <option value="">Choose...</option>
          <option 
            v-for="(label, value) in field.options" 
            :key="value" 
            :value="value"
          >
            {{ label }}
          </option>
        </select>
        <div v-if="hasFieldError(name)" class="invalid-feedback">
          {{ getFieldError(name) }}
        </div>
      </div>

      <!-- Textarea -->
      <div v-if="field.type === 'textarea'" class="form-group">
        <label :for="field.id" class="form-label">
          {{ field.label }}
          <span v-if="field.required" class="text-danger">*</span>
        </label>
        <textarea
          :id="field.id"
          v-model="formData[name]"
          :class="[field.class, { 'is-invalid': hasFieldError(name) }]"
          :placeholder="field.placeholder"
          :required="field.required"
          :rows="field.attributes.rows || 3"
        ></textarea>
        <div v-if="hasFieldError(name)" class="invalid-feedback">
          {{ getFieldError(name) }}
        </div>
      </div>

      <!-- Checkbox -->
      <div v-if="field.type === 'checkbox'" class="form-check">
        <input
          :id="field.id"
          v-model="formData[name]"
          type="checkbox"
          class="form-check-input"
          :value="field.value || true"
        />
        <label :for="field.id" class="form-check-label">
          {{ field.label }}
        </label>
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
        <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-2"></span>
        {{ isSubmitting ? 'Submitting...' : 'Submit' }}
      </button>
    </div>
  </form>
</template>

<script>
export default {
  name: 'DynamicForm',
  data() {
    return {
      formConfig: {},
      formFields: {},
      formData: {},
      formValidation: {},
      errors: {},
      isSubmitting: false
    }
  },
  async mounted() {
    await this.loadForm()
  },
  methods: {
    async loadForm() {
      try {
        const response = await fetch('/api/forms/contact')
        const formSchema = await response.json()
        
        this.formConfig = formSchema.config
        this.formFields = formSchema.fields
        this.formValidation = formSchema.validation
        
        // Initialize form data
        Object.keys(this.formFields).forEach(fieldName => {
          this.$set(this.formData, fieldName, this.formFields[fieldName].value || '')
        })
      } catch (error) {
        console.error('Error loading form:', error)
      }
    },
    
    async submitForm() {
      this.isSubmitting = true
      this.errors = {}
      
      try {
        const response = await fetch(this.formConfig.action, {
          method: this.formConfig.method,
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify(this.formData)
        })
        
        if (response.ok) {
          this.$emit('form-submitted', await response.json())
          this.resetForm()
        } else {
          const errorData = await response.json()
          this.errors = errorData.errors || {}
        }
      } catch (error) {
        console.error('Form submission error:', error)
      } finally {
        this.isSubmitting = false
      }
    },
    
    hasFieldError(fieldName) {
      return this.errors[fieldName] && this.errors[fieldName].length > 0
    },
    
    getFieldError(fieldName) {
      return this.errors[fieldName] ? this.errors[fieldName][0] : ''
    },
    
    resetForm() {
      Object.keys(this.formData).forEach(key => {
        this.formData[key] = ''
      })
      this.errors = {}
    }
  }
}
</script>
```

## ⚛️ React Integration

### React Hook for Form Management

```jsx
import { useState, useEffect } from 'react';

function useForm(formUrl) {
  const [formSchema, setFormSchema] = useState(null);
  const [formData, setFormData] = useState({});
  const [errors, setErrors] = useState({});
  const [isLoading, setIsLoading] = useState(true);
  const [isSubmitting, setIsSubmitting] = useState(false);

  useEffect(() => {
    loadForm();
  }, [formUrl]);

  const loadForm = async () => {
    try {
      const response = await fetch(formUrl);
      const schema = await response.json();
      
      setFormSchema(schema);
      
      // Initialize form data
      const initialData = {};
      Object.keys(schema.fields).forEach(fieldName => {
        initialData[fieldName] = schema.fields[fieldName].value || '';
      });
      setFormData(initialData);
    } catch (error) {
      console.error('Error loading form:', error);
    } finally {
      setIsLoading(false);
    }
  };

  const updateField = (fieldName, value) => {
    setFormData(prev => ({
      ...prev,
      [fieldName]: value
    }));
    
    // Clear field error when user starts typing
    if (errors[fieldName]) {
      setErrors(prev => ({
        ...prev,
        [fieldName]: null
      }));
    }
  };

  const submitForm = async () => {
    setIsSubmitting(true);
    setErrors({});
    
    try {
      const response = await fetch(formSchema.config.action, {
        method: formSchema.config.method,
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
      });
      
      if (response.ok) {
        const result = await response.json();
        return { success: true, data: result };
      } else {
        const errorData = await response.json();
        setErrors(errorData.errors || {});
        return { success: false, errors: errorData.errors };
      }
    } catch (error) {
      console.error('Form submission error:', error);
      return { success: false, error: error.message };
    } finally {
      setIsSubmitting(false);
    }
  };

  return {
    formSchema,
    formData,
    errors,
    isLoading,
    isSubmitting,
    updateField,
    submitForm
  };
}

export default useForm;
```

### React Form Component

```jsx
import React from 'react';
import useForm from './useForm';

function DynamicForm({ formUrl, onSubmit }) {
  const {
    formSchema,
    formData,
    errors,
    isLoading,
    isSubmitting,
    updateField,
    submitForm
  } = useForm(formUrl);

  const handleSubmit = async (e) => {
    e.preventDefault();
    const result = await submitForm();
    if (result.success && onSubmit) {
      onSubmit(result.data);
    }
  };

  const renderField = (fieldName, field) => {
    const commonProps = {
      id: field.id,
      name: fieldName,
      required: field.required,
      disabled: field.disabled,
      className: `${field.class} ${errors[fieldName] ? 'is-invalid' : ''}`,
      value: formData[fieldName] || '',
      onChange: (e) => updateField(fieldName, e.target.value)
    };

    switch (field.type) {
      case 'text':
      case 'email':
      case 'tel':
        return (
          <input
            type={field.type}
            placeholder={field.placeholder}
            {...commonProps}
          />
        );

      case 'textarea':
        return (
          <textarea
            rows={field.attributes?.rows || 3}
            placeholder={field.placeholder}
            {...commonProps}
          />
        );

      case 'select':
        return (
          <select {...commonProps}>
            <option value="">Choose...</option>
            {Object.entries(field.options).map(([value, label]) => (
              <option key={value} value={value}>
                {label}
              </option>
            ))}
          </select>
        );

      case 'checkbox':
        return (
          <input
            type="checkbox"
            className="form-check-input"
            checked={!!formData[fieldName]}
            onChange={(e) => updateField(fieldName, e.target.checked)}
          />
        );

      default:
        return <input type="text" {...commonProps} />;
    }
  };

  if (isLoading) {
    return <div className="text-center">Loading form...</div>;
  }

  if (!formSchema) {
    return <div className="alert alert-danger">Failed to load form</div>;
  }

  return (
    <form onSubmit={handleSubmit} action={formSchema.config.action} method={formSchema.config.method}>
      {Object.entries(formSchema.fields).map(([fieldName, field]) => (
        <div key={fieldName} className="mb-3">
          {field.type !== 'checkbox' ? (
            <div className="form-group">
              <label htmlFor={field.id} className="form-label">
                {field.label}
                {field.required && <span className="text-danger">*</span>}
              </label>
              {renderField(fieldName, field)}
              {errors[fieldName] && (
                <div className="invalid-feedback d-block">
                  {errors[fieldName][0]}
                </div>
              )}
              {field.help && (
                <small className="form-text text-muted">{field.help}</small>
              )}
            </div>
          ) : (
            <div className="form-check">
              {renderField(fieldName, field)}
              <label htmlFor={field.id} className="form-check-label">
                {field.label}
              </label>
            </div>
          )}
        </div>
      ))}
      
      <div className="form-actions">
        <button type="submit" className="btn btn-primary" disabled={isSubmitting}>
          {isSubmitting ? 'Submitting...' : 'Submit'}
        </button>
      </div>
    </form>
  );
}

export default DynamicForm;
```

## 🅰️ Angular Integration

### Angular Service

```typescript
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, BehaviorSubject } from 'rxjs';

export interface FormSchema {
  config: {
    action: string;
    method: string;
    enctype: string;
    csrf: boolean;
    ajax: boolean;
    theme: string;
    multiStep: boolean;
    attributes: any;
  };
  fields: { [key: string]: any };
  validation: {
    rules: { [key: string]: string[] };
    messages: { [key: string]: string };
  };
  data: any;
  meta: any;
}

@Injectable({
  providedIn: 'root'
})
export class FormService {
  private formDataSubject = new BehaviorSubject<any>({});
  public formData$ = this.formDataSubject.asObservable();

  constructor(private http: HttpClient) {}

  loadForm(url: string): Observable<FormSchema> {
    return this.http.get<FormSchema>(url);
  }

  submitForm(schema: FormSchema, data: any): Observable<any> {
    const headers: any = {
      'Content-Type': 'application/json'
    };

    if (schema.config.csrf) {
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      if (csrfToken) {
        headers['X-CSRF-TOKEN'] = csrfToken;
      }
    }

    return this.http.request(schema.config.method, schema.config.action, {
      body: data,
      headers
    });
  }

  updateFormData(data: any) {
    this.formDataSubject.next(data);
  }
}
```

### Angular Component

```typescript
import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { FormService, FormSchema } from './form.service';

@Component({
  selector: 'app-dynamic-form',
  template: `
    <form [formGroup]="dynamicForm" (ngSubmit)="onSubmit()" *ngIf="formSchema">
      <div *ngFor="let field of getFieldsArray()" class="mb-3">
        <!-- Text Input -->
        <div *ngIf="field.type === 'text' || field.type === 'email'" class="form-group">
          <label [for]="field.id" class="form-label">
            {{ field.label }}
            <span *ngIf="field.required" class="text-danger">*</span>
          </label>
          <input
            [id]="field.id"
            [type]="field.type"
            [formControlName]="field.name"
            [class]="field.class + (hasFieldError(field.name) ? ' is-invalid' : '')"
            [placeholder]="field.placeholder"
            [required]="field.required"
          />
          <div *ngIf="hasFieldError(field.name)" class="invalid-feedback">
            {{ getFieldError(field.name) }}
          </div>
          <small *ngIf="field.help" class="form-text text-muted">{{ field.help }}</small>
        </div>

        <!-- Select -->
        <div *ngIf="field.type === 'select'" class="form-group">
          <label [for]="field.id" class="form-label">
            {{ field.label }}
            <span *ngIf="field.required" class="text-danger">*</span>
          </label>
          <select
            [id]="field.id"
            [formControlName]="field.name"
            [class]="field.class + (hasFieldError(field.name) ? ' is-invalid' : '')"
            [required]="field.required"
          >
            <option value="">Choose...</option>
            <option *ngFor="let option of getOptionsArray(field.options)" [value]="option.value">
              {{ option.label }}
            </option>
          </select>
          <div *ngIf="hasFieldError(field.name)" class="invalid-feedback">
            {{ getFieldError(field.name) }}
          </div>
        </div>

        <!-- Textarea -->
        <div *ngIf="field.type === 'textarea'" class="form-group">
          <label [for]="field.id" class="form-label">
            {{ field.label }}
            <span *ngIf="field.required" class="text-danger">*</span>
          </label>
          <textarea
            [id]="field.id"
            [formControlName]="field.name"
            [class]="field.class + (hasFieldError(field.name) ? ' is-invalid' : '')"
            [placeholder]="field.placeholder"
            [rows]="field.attributes?.rows || 3"
          ></textarea>
          <div *ngIf="hasFieldError(field.name)" class="invalid-feedback">
            {{ getFieldError(field.name) }}
          </div>
        </div>

        <!-- Checkbox -->
        <div *ngIf="field.type === 'checkbox'" class="form-check">
          <input
            [id]="field.id"
            type="checkbox"
            [formControlName]="field.name"
            class="form-check-input"
          />
          <label [for]="field.id" class="form-check-label">
            {{ field.label }}
          </label>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary" [disabled]="isSubmitting || dynamicForm.invalid">
          <span *ngIf="isSubmitting" class="spinner-border spinner-border-sm me-2"></span>
          {{ isSubmitting ? 'Submitting...' : 'Submit' }}
        </button>
      </div>
    </form>
  `
})
export class DynamicFormComponent implements OnInit {
  @Input() formUrl!: string;
  @Output() formSubmitted = new EventEmitter<any>();

  formSchema!: FormSchema;
  dynamicForm!: FormGroup;
  isLoading = true;
  isSubmitting = false;
  errors: any = {};

  constructor(
    private fb: FormBuilder,
    private formService: FormService
  ) {}

  ngOnInit() {
    this.loadForm();
  }

  loadForm() {
    this.formService.loadForm(this.formUrl).subscribe({
      next: (schema) => {
        this.formSchema = schema;
        this.buildForm();
        this.isLoading = false;
      },
      error: (error) => {
        console.error('Error loading form:', error);
        this.isLoading = false;
      }
    });
  }

  buildForm() {
    const formControls: any = {};
    
    Object.entries(this.formSchema.fields).forEach(([fieldName, field]: [string, any]) => {
      const validators = [];
      
      if (field.required) {
        validators.push(Validators.required);
      }
      
      if (field.type === 'email') {
        validators.push(Validators.email);
      }
      
      formControls[fieldName] = [field.value || '', validators];
    });
    
    this.dynamicForm = this.fb.group(formControls);
  }

  onSubmit() {
    if (this.dynamicForm.valid) {
      this.isSubmitting = true;
      this.errors = {};
      
      this.formService.submitForm(this.formSchema, this.dynamicForm.value).subscribe({
        next: (response) => {
          this.formSubmitted.emit(response);
          this.dynamicForm.reset();
        },
        error: (error) => {
          this.errors = error.error?.errors || {};
          this.isSubmitting = false;
        },
        complete: () => {
          this.isSubmitting = false;
        }
      });
    }
  }

  getFieldsArray() {
    return Object.entries(this.formSchema.fields).map(([name, field]) => ({
      ...field,
      name
    }));
  }

  getOptionsArray(options: any) {
    return Object.entries(options || {}).map(([value, label]) => ({ value, label }));
  }

  hasFieldError(fieldName: string): boolean {
    return this.errors[fieldName] && this.errors[fieldName].length > 0;
  }

  getFieldError(fieldName: string): string {
    return this.errors[fieldName] ? this.errors[fieldName][0] : '';
  }
}
```

## 📱 Usage Examples

### API Endpoint for Form Schema

```php
// routes/api.php
Route::get('/forms/{formType}', [FormController::class, 'getForm']);

// FormController.php
class FormController extends Controller
{
    public function getForm($formType)
    {
        switch ($formType) {
            case 'contact':
                return $this->getContactForm();
            case 'registration':
                return $this->getRegistrationForm();
            case 'survey':
                return $this->getSurveyForm();
            default:
                abort(404);
        }
    }
    
    private function getContactForm()
    {
        return response()->json(form_array([
            'name' => ['type' => 'text', 'label' => 'Name', 'required' => true],
            'email' => ['type' => 'email', 'label' => 'Email', 'required' => true],
            'message' => ['type' => 'textarea', 'label' => 'Message', 'required' => true]
        ], [
            'action' => '/api/contact',
            'method' => 'POST'
        ]));
    }
}
```

### Multi-Step Form Example

```php
$multiStepForm = Form::create()
    ->multiStep(true)
    ->add('step1_name', 'text', [
        'label' => 'Name',
        'step' => 1,
        'required' => true
    ])
    ->add('step1_email', 'email', [
        'label' => 'Email',
        'step' => 1,
        'required' => true
    ])
    ->add('step2_company', 'text', [
        'label' => 'Company',
        'step' => 2
    ])
    ->add('step3_terms', 'checkbox', [
        'label' => 'Accept Terms',
        'step' => 3,
        'required' => true
    ]);

$formArray = $multiStepForm->toArray();

// The array will include step information in meta.steps
/*
"meta": {
  "steps": [
    {
      "number": 1,
      "fields": ["step1_name", "step1_email"],
      "title": "Step 1"
    },
    {
      "number": 2,
      "fields": ["step2_company"],
      "title": "Step 2"
    },
    {
      "number": 3,
      "fields": ["step3_terms"],
      "title": "Step 3"
    }
  ]
}
*/
```

This comprehensive client-side integration allows you to use Litepie Form with any modern JavaScript framework while maintaining all the powerful features like validation, conditional logic, and multi-step forms.
