<?php

namespace Litepie\Form\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Form Facade
 * 
 * @method static \Litepie\Form\FormBuilder create()
 * @method static \Litepie\Form\FormBuilder quick(array $fields, array $options = [])
 * @method static \Litepie\Form\FormContainer container(string $id = null)
 * @method static \Litepie\Form\FormContainer quickContainer(array $forms, array $options = [])
 * @method static \Litepie\Form\FormManager framework(string $framework)
 * @method static string getFramework()
 */
class Form extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'form';
    }
}
