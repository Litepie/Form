<?php

namespace Litepie\Form\Commands;

use Illuminate\Console\Command;

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
