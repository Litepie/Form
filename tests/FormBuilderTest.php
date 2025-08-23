<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Litepie\Form\Facades\Form;
use Litepie\Form\FormBuilder;
use Litepie\Form\FormManager;

class FormBuilderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_create_a_basic_form()
    {
        $form = Form::create()
            ->action('/test')
            ->method('POST')
            ->add('name', 'text', ['label' => 'Name', 'required' => true])
            ->add('email', 'email', ['label' => 'Email', 'required' => true])
            ->add('submit', 'submit', ['value' => 'Submit']);

        $html = $form->render();

        $this->assertStringContainsString('<form', $html);
        $this->assertStringContainsString('action="/test"', $html);
        $this->assertStringContainsString('method="POST"', $html);
        $this->assertStringContainsString('name="name"', $html);
        $this->assertStringContainsString('name="email"', $html);
        $this->assertStringContainsString('type="submit"', $html);
    }

    /** @test */
    public function it_can_validate_form_data()
    {
        $form = Form::create()
            ->add('name', 'text', ['validation' => 'required|string|max:255'])
            ->add('email', 'email', ['validation' => 'required|email'])
            ->add('age', 'number', ['validation' => 'required|integer|min:18']);

        $validData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'age' => 25
        ];

        $invalidData = [
            'name' => '',
            'email' => 'invalid-email',
            'age' => 15
        ];

        $this->assertTrue($form->validate($validData));
        $this->assertFalse($form->validate($invalidData));
    }

    /** @test */
    public function it_can_create_complex_forms_with_multiple_field_types()
    {
        $form = Form::create()
            ->action('/complex-form')
            ->method('POST')
            ->files(true)
            
            // Basic fields
            ->add('first_name', 'text', [
                'label' => 'First Name',
                'required' => true,
                'placeholder' => 'Enter your first name'
            ])
            ->add('last_name', 'text', [
                'label' => 'Last Name',
                'required' => true
            ])
            ->add('email', 'email', [
                'label' => 'Email Address',
                'required' => true,
                'validation' => 'required|email|unique:users,email'
            ])
            ->add('phone', 'tel', [
                'label' => 'Phone Number',
                'placeholder' => '+1 (555) 123-4567'
            ])
            
            // Selection fields
            ->add('country', 'select', [
                'label' => 'Country',
                'required' => true,
                'options' => [
                    'us' => 'United States',
                    'ca' => 'Canada',
                    'uk' => 'United Kingdom',
                    'au' => 'Australia'
                ]
            ])
            ->add('interests', 'checkbox', [
                'label' => 'Interests',
                'options' => [
                    'tech' => 'Technology',
                    'sports' => 'Sports',
                    'music' => 'Music',
                    'travel' => 'Travel'
                ]
            ])
            ->add('experience', 'radio', [
                'label' => 'Experience Level',
                'options' => [
                    'beginner' => 'Beginner',
                    'intermediate' => 'Intermediate',
                    'advanced' => 'Advanced'
                ]
            ])
            
            // Date and time fields
            ->add('birth_date', 'date', [
                'label' => 'Date of Birth',
                'required' => true
            ])
            ->add('interview_time', 'time', [
                'label' => 'Preferred Interview Time'
            ])
            
            // File uploads
            ->add('resume', 'file', [
                'label' => 'Resume',
                'accept' => '.pdf,.doc,.docx',
                'required' => true
            ])
            ->add('profile_photo', 'image', [
                'label' => 'Profile Photo',
                'crop' => true,
                'aspectRatio' => '1:1'
            ])
            
            // Rich content
            ->add('bio', 'textarea', [
                'label' => 'Biography',
                'rows' => 5,
                'placeholder' => 'Tell us about yourself...'
            ])
            ->add('portfolio_description', 'richtext', [
                'label' => 'Portfolio Description',
                'height' => 300
            ])
            
            // Advanced fields
            ->add('salary_range', 'range', [
                'label' => 'Expected Salary Range',
                'min' => 30000,
                'max' => 150000,
                'step' => 5000,
                'showValue' => true,
                'valuePrefix' => '$'
            ])
            ->add('skills', 'tags', [
                'label' => 'Skills',
                'placeholder' => 'Add your skills...',
                'suggestions' => ['PHP', 'Laravel', 'JavaScript', 'React', 'Vue']
            ])
            ->add('location', 'map', [
                'label' => 'Office Location',
                'zoom' => 10
            ])
            
            // Form controls
            ->add('terms', 'checkbox', [
                'label' => 'I agree to the terms and conditions',
                'required' => true,
                'validation' => 'required|accepted'
            ])
            ->add('submit', 'submit', [
                'value' => 'Submit Application',
                'class' => 'btn btn-primary'
            ]);

        $html = $form->render();

        // Test that all field types are rendered
        $this->assertStringContainsString('type="text"', $html);
        $this->assertStringContainsString('type="email"', $html);
        $this->assertStringContainsString('type="tel"', $html);
        $this->assertStringContainsString('<select', $html);
        $this->assertStringContainsString('type="checkbox"', $html);
        $this->assertStringContainsString('type="radio"', $html);
        $this->assertStringContainsString('type="date"', $html);
        $this->assertStringContainsString('type="time"', $html);
        $this->assertStringContainsString('type="file"', $html);
        $this->assertStringContainsString('<textarea', $html);
        $this->assertStringContainsString('type="range"', $html);
        $this->assertStringContainsString('type="submit"', $html);
    }

    /** @test */
    public function it_can_handle_conditional_logic()
    {
        $form = Form::create()
            ->add('user_type', 'select', [
                'label' => 'User Type',
                'options' => [
                    'student' => 'Student',
                    'professional' => 'Professional'
                ]
            ])
            ->add('school', 'text', [
                'label' => 'School',
                'show_if' => 'user_type:student'
            ])
            ->add('company', 'text', [
                'label' => 'Company',
                'show_if' => 'user_type:professional'
            ]);

        $html = $form->render();
        
        $this->assertStringContainsString('data-show-if="user_type:student"', $html);
        $this->assertStringContainsString('data-show-if="user_type:professional"', $html);
    }

    /** @test */
    public function it_can_populate_form_with_existing_data()
    {
        $existingData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'bio' => 'Software developer with 5 years experience.'
        ];

        $form = Form::create()
            ->add('name', 'text', ['label' => 'Name'])
            ->add('email', 'email', ['label' => 'Email'])
            ->add('bio', 'textarea', ['label' => 'Bio'])
            ->populate($existingData);

        $html = $form->render();

        $this->assertStringContainsString('value="John Doe"', $html);
        $this->assertStringContainsString('value="john@example.com"', $html);
        $this->assertStringContainsString('Software developer with 5 years experience.', $html);
    }

    /** @test */
    public function it_can_use_different_themes()
    {
        $form = Form::create()
            ->theme('bootstrap5')
            ->add('name', 'text', ['label' => 'Name'])
            ->add('submit', 'submit', ['value' => 'Submit']);

        $html = $form->render();
        $this->assertStringContainsString('class="form-control"', $html);

        $form = Form::create()
            ->theme('tailwind')
            ->add('name', 'text', ['label' => 'Name'])
            ->add('submit', 'submit', ['value' => 'Submit']);

        $html = $form->render();
        $this->assertStringContainsString('class="border rounded px-3 py-2"', $html);
    }

    /** @test */
    public function it_can_handle_file_uploads()
    {
        $form = Form::create()
            ->files(true)
            ->add('document', 'file', [
                'label' => 'Document',
                'accept' => '.pdf,.doc',
                'maxSize' => 10
            ])
            ->add('images', 'gallery', [
                'label' => 'Images',
                'maxFiles' => 5,
                'sortable' => true
            ]);

        $html = $form->render();

        $this->assertStringContainsString('enctype="multipart/form-data"', $html);
        $this->assertStringContainsString('accept=".pdf,.doc"', $html);
        $this->assertStringContainsString('data-max-size="10"', $html);
        $this->assertStringContainsString('data-max-files="5"', $html);
    }

    /** @test */
    public function it_can_create_multi_step_forms()
    {
        $form = Form::create()
            ->multiStep(true)
            ->add('step1_name', 'text', [
                'label' => 'Name',
                'step' => 1
            ])
            ->add('step1_email', 'email', [
                'label' => 'Email',
                'step' => 1
            ])
            ->add('step2_company', 'text', [
                'label' => 'Company',
                'step' => 2
            ])
            ->add('step2_position', 'text', [
                'label' => 'Position',
                'step' => 2
            ]);

        $html = $form->render();

        $this->assertStringContainsString('data-step="1"', $html);
        $this->assertStringContainsString('data-step="2"', $html);
        $this->assertStringContainsString('multi-step-form', $html);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $form = Form::create()
            ->add('name', 'text', ['required' => true])
            ->add('email', 'email', ['required' => true]);

        $this->assertFalse($form->validate(['name' => '', 'email' => '']));
        $this->assertTrue($form->validate(['name' => 'John', 'email' => 'john@example.com']));
    }

    /** @test */
    public function it_can_add_custom_validation_rules()
    {
        $form = Form::create()
            ->add('username', 'text', [
                'validation' => 'required|unique:users,username|min:3'
            ])
            ->add('password', 'password', [
                'validation' => 'required|min:8|confirmed'
            ])
            ->add('password_confirmation', 'password');

        $validationRules = $form->getValidationRules();

        $this->assertArrayHasKey('username', $validationRules);
        $this->assertArrayHasKey('password', $validationRules);
        $this->assertContains('required', $validationRules['username']);
        $this->assertContains('unique:users,username', $validationRules['username']);
        $this->assertContains('min:8', $validationRules['password']);
        $this->assertContains('confirmed', $validationRules['password']);
    }

    /** @test */
    public function it_can_handle_ajax_submissions()
    {
        $form = Form::create()
            ->ajax(true)
            ->action('/api/contact')
            ->add('name', 'text', ['required' => true])
            ->add('message', 'textarea', ['required' => true]);

        $html = $form->render();

        $this->assertStringContainsString('data-ajax="true"', $html);
        $this->assertStringContainsString('litepie-form-ajax', $html);
    }

    /** @test */
    public function it_includes_csrf_protection_by_default()
    {
        $form = Form::create()
            ->action('/test')
            ->method('POST')
            ->add('name', 'text');

        $html = $form->render();

        $this->assertStringContainsString('name="_token"', $html);
        $this->assertStringContainsString('type="hidden"', $html);
    }

    /** @test */
    public function it_can_disable_csrf_protection()
    {
        $form = Form::create()
            ->action('/test')
            ->method('POST')
            ->csrf(false)
            ->add('name', 'text');

        $html = $form->render();

        $this->assertStringNotContainsString('name="_token"', $html);
    }

    /** @test */
    public function it_can_render_forms_with_custom_attributes()
    {
        $form = Form::create()
            ->attribute('data-custom', 'value')
            ->attribute('class', 'my-custom-form')
            ->add('name', 'text', [
                'data-validation' => 'required',
                'class' => 'custom-input'
            ]);

        $html = $form->render();

        $this->assertStringContainsString('data-custom="value"', $html);
        $this->assertStringContainsString('class="my-custom-form"', $html);
        $this->assertStringContainsString('data-validation="required"', $html);
        $this->assertStringContainsString('class="custom-input"', $html);
    }
}

/**
 * Integration test for form rendering and processing
 */
class FormIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_process_a_complete_contact_form_submission()
    {
        // Create the form
        $form = Form::create()
            ->action('/contact')
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
                'validation' => 'required|string|min:10'
            ])
            ->add('newsletter', 'checkbox', [
                'label' => 'Subscribe to newsletter'
            ])
            ->add('submit', 'submit', [
                'value' => 'Send Message'
            ]);

        // Test form rendering
        $html = $form->render();
        $this->assertStringContainsString('<form', $html);
        $this->assertStringContainsString('action="/contact"', $html);

        // Test form validation
        $validData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'general',
            'message' => 'This is a test message that is long enough.',
            'newsletter' => '1'
        ];

        $invalidData = [
            'name' => '',
            'email' => 'invalid-email',
            'subject' => 'invalid-option',
            'message' => 'short'
        ];

        $this->assertTrue($form->validate($validData));
        $this->assertFalse($form->validate($invalidData));

        // Test form population
        $form->populate($validData);
        $populatedHtml = $form->render();
        
        $this->assertStringContainsString('value="John Doe"', $populatedHtml);
        $this->assertStringContainsString('value="john@example.com"', $populatedHtml);
        $this->assertStringContainsString('selected', $populatedHtml);
        $this->assertStringContainsString('This is a test message', $populatedHtml);
    }

    /** @test */
    public function it_can_handle_file_upload_forms()
    {
        $form = Form::create()
            ->action('/upload')
            ->method('POST')
            ->files(true)
            ->add('title', 'text', [
                'label' => 'Document Title',
                'required' => true
            ])
            ->add('document', 'file', [
                'label' => 'Upload Document',
                'required' => true,
                'accept' => '.pdf,.doc,.docx',
                'maxSize' => 10
            ])
            ->add('images', 'gallery', [
                'label' => 'Upload Images',
                'maxFiles' => 5,
                'accept' => 'image/*'
            ])
            ->add('submit', 'submit', [
                'value' => 'Upload Files'
            ]);

        $html = $form->render();

        // Test that form has proper encoding
        $this->assertStringContainsString('enctype="multipart/form-data"', $html);
        $this->assertStringContainsString('type="file"', $html);
        $this->assertStringContainsString('accept=".pdf,.doc,.docx"', $html);
        $this->assertStringContainsString('data-max-size="10"', $html);
    }
}
