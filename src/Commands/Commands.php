<?php

namespace Litepie\Form\Commands;

use Illuminate\Console\Command;

/**
 * Install Command
 */
class InstallCommand extends Command
{
    protected $signature = 'form:install {--force : Overwrite existing files}';
    protected $description = 'Install the Litepie Form package';

    public function handle(): int
    {
        $this->info('Installing Litepie Form package...');

        // Publish configuration
        $this->call('vendor:publish', [
            '--provider' => 'Litepie\Form\FormServiceProvider',
            '--tag' => 'form-config',
            '--force' => $this->option('force'),
        ]);

        // Publish views
        $this->call('vendor:publish', [
            '--provider' => 'Litepie\Form\FormServiceProvider',
            '--tag' => 'form-views',
            '--force' => $this->option('force'),
        ]);

        // Publish assets
        $this->call('vendor:publish', [
            '--provider' => 'Litepie\Form\FormServiceProvider',
            '--tag' => 'form-assets',
            '--force' => $this->option('force'),
        ]);

        $this->info('Litepie Form package installed successfully!');
        $this->newLine();
        $this->info('Next steps:');
        $this->line('1. Configure your form settings in config/form.php');
        $this->line('2. Include form assets in your layout: {!! form_include_assets() !!}');
        $this->line('3. Start building forms with Form::create()');

        return Command::SUCCESS;
    }
}

/**
 * Make Form Command
 */
class MakeFormCommand extends Command
{
    protected $signature = 'make:form {name : The name of the form class}';
    protected $description = 'Create a new form class';

    public function handle(): int
    {
        $name = $this->argument('name');
        $className = str_replace('/', '\\', $name);
        $path = app_path('Forms/' . str_replace('\\', '/', $name) . '.php');
        
        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $stub = $this->getStub();
        $content = str_replace(
            ['{{class}}', '{{namespace}}'],
            [class_basename($className), 'App\\Forms'],
            $stub
        );

        file_put_contents($path, $content);

        $this->info("Form class created: {$path}");

        return Command::SUCCESS;
    }

    protected function getStub(): string
    {
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
}

/**
 * Publish Command
 */
class PublishCommand extends Command
{
    protected $signature = 'form:publish {--tag=all : The tag to publish}';
    protected $description = 'Publish form package assets';

    public function handle(): int
    {
        $tag = $this->option('tag');
        
        if ($tag === 'all') {
            $tags = ['form-config', 'form-views', 'form-assets', 'form-lang'];
        } else {
            $tags = [$tag];
        }

        foreach ($tags as $publishTag) {
            $this->call('vendor:publish', [
                '--provider' => 'Litepie\Form\FormServiceProvider',
                '--tag' => $publishTag,
                '--force' => true,
            ]);
        }

        $this->info('Form package assets published successfully!');

        return Command::SUCCESS;
    }
}
