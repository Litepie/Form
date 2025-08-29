<?php

namespace Litepie\Form\Commands;

use Illuminate\Console\Command;

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