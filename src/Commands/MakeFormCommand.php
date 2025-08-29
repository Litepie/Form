<?php

namespace Litepie\Form\Commands;

use Illuminate\Console\Command;

class MakeFormCommand extends Command
{
    protected $signature = 'make:form {name : The name of the form class} {--container : Create a form container}';
    protected $description = 'Create a new form class';

    public function handle(): int
    {
        $name = $this->argument('name');
        $isContainer = $this->option('container');
        
        $className = str_replace('/', '\\', $name);
        $path = app_path('Forms/' . str_replace('\\', '/', $name) . '.php');
        
        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $stub = $isContainer ? $this->getContainerStub() : $this->getFormStub();
        $content = str_replace(
            ['{{class}}', '{{namespace}}'],
            [class_basename($className), 'App\\Forms'],
            $stub
        );

        file_put_contents($path, $content);

        $type = $isContainer ? 'Form container' : 'Form';
        $this->info("{$type} class created: {$path}");

        return Command::SUCCESS;
    }

    protected function getFormStub(): string
    {
        $stubPath = __DIR__ . '/../../stubs/form.stub';
        if (file_exists($stubPath)) {
            return file_get_contents($stubPath);
        }

        return <<<'STUB'
<?php

namespace {{namespace}};

use Litepie\Form\FormBuilder;

class {{class}}
{
    /**
     * Build the form.
     */
    public static function build(): FormBuilder
    {
        return form_create()
            ->action('/')
            ->method('POST')
            ->add('name', 'text', [
                'label' => 'Name',
                'required' => true,
                'validation' => 'required|string|max:255'
            ])
            ->add('email', 'email', [
                'label' => 'Email',
                'required' => true,
                'validation' => 'required|email|unique:users'
            ])
            ->add('submit', 'submit', [
                'value' => 'Submit',
                'class' => 'btn btn-primary'
            ]);
    }
}
STUB;
    }

    protected function getContainerStub(): string
    {
        $stubPath = __DIR__ . '/../../stubs/form-container.stub';
        if (file_exists($stubPath)) {
            return file_get_contents($stubPath);
        }

        return <<<'STUB'
<?php

namespace {{namespace}};

use Litepie\Form\FormContainer;
use Litepie\Form\FormBuilder;

class {{class}} extends FormContainer
{
    /**
     * Configure the form container.
     */
    protected function configure(): void
    {
        $this->setDisplayMode('tabs')
             ->setContainerClass('form-container')
             ->setFormClass('form-group')
             ->enableCaching(true);
    }

    /**
     * Define the forms.
     */
    protected function defineForms(): array
    {
        return [
            'basic' => $this->createBasicForm(),
            'advanced' => $this->createAdvancedForm(),
        ];
    }

    /**
     * Create the basic form.
     */
    private function createBasicForm(): FormBuilder
    {
        return form_create()
            ->action('/basic')
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
            ]);
    }

    /**
     * Create the advanced form.
     */
    private function createAdvancedForm(): FormBuilder
    {
        return form_create()
            ->action('/advanced')
            ->method('POST')
            ->add('description', 'textarea', [
                'label' => 'Description',
                'rows' => 5
            ])
            ->add('category', 'select', [
                'label' => 'Category',
                'options' => [
                    'tech' => 'Technology',
                    'business' => 'Business',
                    'other' => 'Other'
                ]
            ]);
    }
}
STUB;
    }
}
