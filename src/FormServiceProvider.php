<?php

namespace Litepie\Form;

use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use Litepie\Form\Commands\InstallCommand;
use Litepie\Form\Commands\MakeFormCommand;
use Litepie\Form\Commands\PublishCommand;

/**
 * Form Service Provider
 * 
 * Registers the Form builder package with Laravel
 */
class FormServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     */
    protected bool $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'form');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'form');
        
        $this->publishResources();
        $this->registerCommands();
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/form.php', 'form');
        
        $this->registerBindings();
        $this->registerMacros();
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            'form',
            'form.builder',
            'form.field',
            'form.validator',
            'form.renderer',
            'form.container'
        ];
    }

    /**
     * Register package bindings.
     */
    protected function registerBindings(): void
    {
        $this->app->singleton('form', function ($app) {
            return new FormManager($app);
        });

        $this->app->singleton('form.builder', function ($app) {
            return new FormBuilder($app);
        });

        $this->app->singleton('form.field', function ($app) {
            return new FieldFactory($app);
        });

        $this->app->singleton('form.validator', function ($app) {
            return new FormValidator($app);
        });

        $this->app->singleton('form.renderer', function ($app) {
            return new FormRenderer($app);
        });

        $this->app->singleton('form.container', function ($app) {
            return new FormContainer($app);
        });
    }

    /**
     * Register form macros.
     */
    protected function registerMacros(): void
    {
        // Add any Laravel macros here
    }

    /**
     * Publish package resources.
     */
    protected function publishResources(): void
    {
        // Publish configuration
        $this->publishes([
            __DIR__ . '/../config/form.php' => config_path('form.php'),
        ], 'form-config');

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/form'),
        ], 'form-views');

        // Publish translations
        $this->publishes([
            __DIR__ . '/../resources/lang' => $this->app->langPath('vendor/form'),
        ], 'form-lang');

        // Publish assets
        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('vendor/form'),
        ], 'form-assets');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'form-migrations');
    }

    /**
     * Register package commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\InstallCommand::class,
                Commands\MakeFormCommand::class,
                Commands\PublishCommand::class,
            ]);
        }
    }
}
