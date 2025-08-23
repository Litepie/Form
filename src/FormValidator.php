<?php

namespace Litepie\Form;

use Illuminate\Container\Container;

/**
 * Form Validator
 * 
 * Handles form validation
 */
class FormValidator
{
    /**
     * The application instance.
     */
    protected Container $app;

    /**
     * Create a new form validator instance.
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Validate form data.
     */
    public function validate(FormBuilder $form, array $data): array
    {
        $rules = $form->rules();
        
        if (empty($rules)) {
            return [];
        }

        $validator = $this->app['validator']->make($data, $rules);
        
        return $validator->errors()->toArray();
    }
}
