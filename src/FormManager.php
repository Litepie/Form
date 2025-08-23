<?php

namespace Litepie\Form;

use Illuminate\Container\Container;
use Illuminate\Support\Collection;

/**
 * Form Manager
 * 
 * Main entry point for creating and managing forms
 */
class FormManager
{
    /**
     * The application instance.
     */
    protected Container $app;

    /**
     * The default framework.
     */
    protected string $framework = 'bootstrap5';

    /**
     * Create a new form manager instance.
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
        $this->framework = config('form.framework', 'bootstrap5');
    }

    /**
     * Create a new form builder instance.
     */
    public function create(): FormBuilder
    {
        return $this->app->make('form.builder');
    }

    /**
     * Set the default framework.
     */
    public function framework(string $framework): self
    {
        $this->framework = $framework;
        return $this;
    }

    /**
     * Get the current framework.
     */
    public function getFramework(): string
    {
        return $this->framework;
    }

    /**
     * Create a quick form.
     */
    public function quick(array $fields, array $options = []): FormBuilder
    {
        $form = $this->create();

        if (isset($options['action'])) {
            $form->action($options['action']);
        }

        if (isset($options['method'])) {
            $form->method($options['method']);
        }

        foreach ($fields as $name => $field) {
            if (is_string($field)) {
                $form->add($name, $field);
            } elseif (is_array($field)) {
                $type = $field['type'] ?? 'text';
                unset($field['type']);
                $form->add($name, $type, $field);
            }
        }

        return $form;
    }

    /**
     * Create a new form container instance.
     */
    public function container(string $id = null): FormContainer
    {
        return new FormContainer($this->app, $id);
    }

    /**
     * Create a quick container with multiple forms.
     */
    public function quickContainer(array $forms, array $options = []): FormContainer
    {
        $container = $this->container($options['id'] ?? null);

        // Set container options
        if (isset($options['name'])) {
            $container->name($options['name']);
        }

        if (isset($options['description'])) {
            $container->description($options['description']);
        }

        if (isset($options['tabbed'])) {
            $container->tabbed($options['tabbed']);
        }

        if (isset($options['accordion'])) {
            $container->accordion($options['accordion']);
        }

        if (isset($options['framework'])) {
            $container->framework($options['framework']);
        }

        if (isset($options['validationMode'])) {
            $container->validationMode($options['validationMode']);
        }

        // Add forms to container
        foreach ($forms as $key => $formConfig) {
            if ($formConfig instanceof FormBuilder) {
                // Form instance provided
                $container->addForm($key, $formConfig);
            } elseif (is_array($formConfig)) {
                // Form configuration provided
                $fields = $formConfig['fields'] ?? [];
                $formOptions = $formConfig['options'] ?? [];
                $containerOptions = $formConfig['containerOptions'] ?? [];

                $form = $this->quick($fields, $formOptions);
                $container->addForm($key, $form, $containerOptions);
            }
        }

        return $container;
    }
}
