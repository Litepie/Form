<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Litepie\Form\Facades\Form;
use Litepie\Form\FormContainer;
use Litepie\Form\FormBuilder;

class FormContainerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_basic_form_container()
    {
        $container = Form::container('test-container')
            ->name('Test Container')
            ->description('A test form container');

        $this->assertInstanceOf(FormContainer::class, $container);
        $this->assertEquals('test-container', $container->getId());
        $this->assertEquals('Test Container', $container->getName());
        $this->assertEquals('A test form container', $container->getDescription());
    }

    /** @test */
    public function it_can_add_multiple_forms_to_container()
    {
        $container = Form::container();

        // Create first form
        $contactForm = Form::create()
            ->add('name', 'text', ['label' => 'Name', 'required' => true])
            ->add('email', 'email', ['label' => 'Email', 'required' => true]);

        // Create second form
        $feedbackForm = Form::create()
            ->add('rating', 'range', ['label' => 'Rating', 'min' => 1, 'max' => 5])
            ->add('comment', 'textarea', ['label' => 'Comment']);

        $container->addForm('contact', $contactForm, [
            'title' => 'Contact Information',
            'description' => 'Please provide your contact details'
        ]);

        $container->addForm('feedback', $feedbackForm, [
            'title' => 'Feedback',
            'description' => 'Please rate your experience'
        ]);

        $this->assertTrue($container->hasForm('contact'));
        $this->assertTrue($container->hasForm('feedback'));
        $this->assertCount(2, $container->getForms());
        $this->assertEquals(['contact', 'feedback'], $container->getFormKeys());
    }

    /** @test */
    public function it_can_create_forms_directly_in_container()
    {
        $container = Form::container();

        $contactForm = $container->createForm('contact', [
            'title' => 'Contact Form',
            'description' => 'Get in touch with us'
        ]);

        $contactForm
            ->add('name', 'text', ['label' => 'Name', 'required' => true])
            ->add('message', 'textarea', ['label' => 'Message', 'required' => true]);

        $this->assertTrue($container->hasForm('contact'));
        $this->assertInstanceOf(FormBuilder::class, $container->getForm('contact'));
        $this->assertCount(2, $container->getForm('contact')->fields());
    }

    /** @test */
    public function it_can_create_quick_container_with_multiple_forms()
    {
        $container = Form::quickContainer([
            'personal' => [
                'fields' => [
                    'first_name' => 'text',
                    'last_name' => 'text',
                    'email' => ['type' => 'email', 'required' => true]
                ],
                'options' => [
                    'action' => '/personal',
                    'method' => 'POST'
                ],
                'containerOptions' => [
                    'title' => 'Personal Information',
                    'description' => 'Basic personal details'
                ]
            ],
            'preferences' => [
                'fields' => [
                    'newsletter' => ['type' => 'checkbox', 'label' => 'Subscribe to newsletter'],
                    'notifications' => ['type' => 'select', 'options' => [
                        'email' => 'Email',
                        'sms' => 'SMS',
                        'none' => 'None'
                    ]]
                ],
                'containerOptions' => [
                    'title' => 'Preferences',
                    'description' => 'Choose your preferences'
                ]
            ]
        ], [
            'name' => 'User Registration',
            'tabbed' => true
        ]);

        $this->assertInstanceOf(FormContainer::class, $container);
        $this->assertEquals('User Registration', $container->getName());
        $this->assertTrue($container->isTabbed());
        $this->assertCount(2, $container->getForms());
        $this->assertTrue($container->hasForm('personal'));
        $this->assertTrue($container->hasForm('preferences'));
    }

    /** @test */
    public function it_can_use_tabbed_interface()
    {
        $container = Form::container()
            ->name('Multi-Step Registration')
            ->tabbed(true)
            ->activeForm('step1');

        $step1 = $container->createForm('step1', ['title' => 'Personal Info']);
        $step2 = $container->createForm('step2', ['title' => 'Account Details']);

        $this->assertTrue($container->isTabbed());
        $this->assertFalse($container->isAccordion());
        $this->assertEquals('step1', $container->getActiveForm());
    }

    /** @test */
    public function it_can_use_accordion_interface()
    {
        $container = Form::container()
            ->name('Settings')
            ->accordion(true);

        $general = $container->createForm('general', ['title' => 'General Settings']);
        $privacy = $container->createForm('privacy', ['title' => 'Privacy Settings']);

        $this->assertTrue($container->isAccordion());
        $this->assertFalse($container->isTabbed());
    }

    /** @test */
    public function it_can_populate_all_forms_with_data()
    {
        $container = Form::container();

        $personalForm = $container->createForm('personal');
        $personalForm->add('name', 'text', ['label' => 'Name'])
                    ->add('email', 'email', ['label' => 'Email']);

        $addressForm = $container->createForm('address');
        $addressForm->add('street', 'text', ['label' => 'Street'])
                   ->add('city', 'text', ['label' => 'City']);

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

        // Check if forms are populated correctly
        $personalFields = $personalForm->fields();
        $this->assertEquals('John Doe', $personalFields->get('name')->getValue());
        $this->assertEquals('john@example.com', $personalFields->get('email')->getValue());

        $addressFields = $addressForm->fields();
        $this->assertEquals('123 Main St', $addressFields->get('street')->getValue());
        $this->assertEquals('Anytown', $addressFields->get('city')->getValue());
    }

    /** @test */
    public function it_can_validate_forms_individually()
    {
        $container = Form::container()
            ->validationMode('individual');

        $form1 = $container->createForm('form1');
        $form1->add('required_field', 'text', ['validation' => 'required']);

        $form2 = $container->createForm('form2');
        $form2->add('email_field', 'email', ['validation' => 'required|email']);

        $data = [
            'form1' => ['required_field' => 'valid'],
            'form2' => ['email_field' => 'invalid-email']
        ];

        $results = $container->validate($data);

        $this->assertTrue($results['form1']);
        $this->assertFalse($results['form2']);
    }

    /** @test */
    public function it_can_validate_forms_in_combined_mode()
    {
        $container = Form::container()
            ->validationMode('combined');

        $form1 = $container->createForm('form1');
        $form1->add('field1', 'text', ['validation' => 'required']);

        $form2 = $container->createForm('form2');
        $form2->add('field2', 'email', ['validation' => 'required|email']);

        $data = [
            'form1' => ['field1' => ''],
            'form2' => ['field2' => 'invalid-email']
        ];

        $results = $container->validate($data);

        $this->assertFalse($results['_combined']['valid']);
        $this->assertArrayHasKey('form1', $results['_combined']['errors']);
        $this->assertArrayHasKey('form2', $results['_combined']['errors']);
    }

    /** @test */
    public function it_can_validate_forms_sequentially()
    {
        $container = Form::container()
            ->validationMode('sequential');

        $form1 = $container->createForm('form1');
        $form1->add('field1', 'text', ['validation' => 'required']);

        $form2 = $container->createForm('form2');
        $form2->add('field2', 'text', ['validation' => 'required']);

        $data = [
            'form1' => ['field1' => ''], // This will fail
            'form2' => ['field2' => 'valid'] // This won't be validated
        ];

        $results = $container->validate($data);

        $this->assertFalse($results['form1']);
        $this->assertArrayNotHasKey('form2', $results); // Should stop at first failure
    }

    /** @test */
    public function it_can_remove_forms_from_container()
    {
        $container = Form::container();

        $form1 = $container->createForm('form1');
        $form2 = $container->createForm('form2');

        $this->assertCount(2, $container->getForms());
        $this->assertTrue($container->hasForm('form1'));

        $container->removeForm('form1');

        $this->assertCount(1, $container->getForms());
        $this->assertFalse($container->hasForm('form1'));
        $this->assertTrue($container->hasForm('form2'));
    }

    /** @test */
    public function it_can_reorder_forms()
    {
        $container = Form::container();

        $container->createForm('first');
        $container->createForm('second');
        $container->createForm('third');

        $this->assertEquals(['first', 'second', 'third'], $container->getFormKeys());

        $container->reorderForms(['third', 'first', 'second']);

        $this->assertEquals(['third', 'first', 'second'], $container->getFormKeys());
    }

    /** @test */
    public function it_can_filter_visible_forms()
    {
        $container = Form::container();

        $container->addForm('visible', Form::create(), ['visible' => true]);
        $container->addForm('hidden', Form::create(), ['visible' => false]);

        $visibleForms = $container->getVisibleForms();

        $this->assertCount(1, $visibleForms);
        $this->assertTrue($visibleForms->has('visible'));
        $this->assertFalse($visibleForms->has('hidden'));
    }

    /** @test */
    public function it_can_convert_to_array_for_client_side()
    {
        $container = Form::container('test-container')
            ->name('Test Forms')
            ->description('Multiple forms for testing')
            ->tabbed(true);

        $contactForm = $container->createForm('contact', [
            'title' => 'Contact Form',
            'description' => 'Get in touch'
        ]);
        $contactForm->add('name', 'text', ['required' => true])
                   ->add('email', 'email', ['required' => true]);

        $array = $container->toArray();

        $this->assertArrayHasKey('container', $array);
        $this->assertArrayHasKey('forms', $array);

        $this->assertEquals('test-container', $array['container']['id']);
        $this->assertEquals('Test Forms', $array['container']['name']);
        $this->assertTrue($array['container']['tabbed']);
        $this->assertEquals(1, $array['container']['formCount']);

        $this->assertArrayHasKey('contact', $array['forms']);
        $this->assertEquals('Contact Form', $array['forms']['contact']['meta']['title']);
        $this->assertEquals('Get in touch', $array['forms']['contact']['meta']['description']);
    }

    /** @test */
    public function it_can_convert_to_json()
    {
        $container = Form::container()
            ->name('JSON Test');

        $form = $container->createForm('test');
        $form->add('field', 'text', ['label' => 'Test Field']);

        $json = $container->toJson();
        $decoded = json_decode($json, true);

        $this->assertIsArray($decoded);
        $this->assertEquals('JSON Test', $decoded['container']['name']);
        $this->assertArrayHasKey('test', $decoded['forms']);
    }

    /** @test */
    public function it_applies_framework_to_all_forms()
    {
        $container = Form::container()
            ->framework('tailwind');

        $form1 = $container->createForm('form1');
        $form2 = $container->createForm('form2');

        $this->assertEquals('tailwind', $container->getFramework());
        $this->assertEquals('tailwind', $form1->getFramework());
        $this->assertEquals('tailwind', $form2->getFramework());
    }

    /** @test */
    public function it_can_render_container()
    {
        $container = Form::container('test-render')
            ->name('Render Test');

        $form = $container->createForm('test');
        $form->add('name', 'text', ['label' => 'Name']);

        $html = $container->render();

        $this->assertStringContainsString('form-container', $html);
        $this->assertStringContainsString('Render Test', $html);
        $this->assertStringContainsString('test-render', $html);
    }
}

/**
 * Integration test for extended form containers
 */
class CustomFormContainerTest extends TestCase
{
    /** @test */
    public function it_can_extend_form_container_for_specific_use_cases()
    {
        $container = new CustomRegistrationContainer(app());

        $container->setupRegistrationForms();

        $this->assertTrue($container->hasForm('personal'));
        $this->assertTrue($container->hasForm('account'));
        $this->assertTrue($container->hasForm('preferences'));
        $this->assertEquals('Personal Information', $container->getFormMeta('personal')['title']);
    }
}

/**
 * Example of extending FormContainer for specific use case
 */
class CustomRegistrationContainer extends FormContainer
{
    public function setupRegistrationForms(): self
    {
        // Personal Information Form
        $personalForm = $this->createForm('personal', [
            'title' => 'Personal Information',
            'description' => 'Basic personal details',
            'icon' => 'user'
        ]);

        $personalForm
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

        // Account Information Form
        $accountForm = $this->createForm('account', [
            'title' => 'Account Details',
            'description' => 'Login credentials and security',
            'icon' => 'lock'
        ]);

        $accountForm
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

        // Preferences Form
        $preferencesForm = $this->createForm('preferences', [
            'title' => 'Preferences',
            'description' => 'Customize your experience',
            'icon' => 'settings'
        ]);

        $preferencesForm
            ->add('newsletter', 'checkbox', [
                'label' => 'Subscribe to newsletter',
                'value' => '1'
            ])
            ->add('notifications', 'select', [
                'label' => 'Notification Preference',
                'options' => [
                    'all' => 'All notifications',
                    'important' => 'Important only',
                    'none' => 'No notifications'
                ],
                'value' => 'important'
            ])
            ->add('theme', 'radio', [
                'label' => 'Theme Preference',
                'options' => [
                    'light' => 'Light theme',
                    'dark' => 'Dark theme',
                    'auto' => 'Auto (system preference)'
                ],
                'value' => 'auto'
            ]);

        // Set as tabbed interface
        $this->tabbed(true)
             ->activeForm('personal')
             ->validationMode('sequential');

        return $this;
    }

    public function getFormMeta(string $key): ?array
    {
        $forms = $this->getFormsWithMeta();
        return $forms->has($key) ? $forms->get($key) : null;
    }

    public function validateRegistration(array $data): array
    {
        // Custom validation logic for registration
        $results = $this->validate($data);
        
        // Add custom business logic validation
        if (isset($data['personal']['birth_date'])) {
            $age = \Carbon\Carbon::parse($data['personal']['birth_date'])->age;
            if ($age < 13) {
                $results['personal'] = false;
                $results['_custom_errors'] = ['age' => 'Must be at least 13 years old'];
            }
        }

        return $results;
    }

    public function getProgressPercentage(): int
    {
        $totalForms = $this->forms->count();
        $activeFormIndex = array_search($this->getActiveForm(), $this->getFormKeys());
        
        return (int) (($activeFormIndex + 1) / $totalForms * 100);
    }
}
