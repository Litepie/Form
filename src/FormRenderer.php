<?php

namespace Litepie\Form;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\View;

/**
 * Form Renderer
 * 
 * Handles form and field rendering
 */
class FormRenderer
{
    /**
     * The application instance.
     */
    protected Container $app;

    /**
     * Create a new form renderer instance.
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Render a complete form.
     */
    public function render(FormBuilder $form): string
    {
        // Get user from form data if available
        $user = $form->getData()['_user'] ?? null;
        
        $html = $form->open();
        
        // Get visible fields only if user is provided
        $fields = $user ? $form->visibleFields($user) : $form->fields();
        
        foreach ($fields as $field) {
            // Skip hidden fields
            if (!$field->isVisible($user)) {
                continue;
            }
            
            $html .= $this->renderField($field);
        }
        
        $html .= $form->close();
        
        return $html;
    }

    /**
     * Render a single field.
     */
    public function renderField(Field $field): string
    {
        $viewName = 'form::fields.' . $field->getType();
        
        if (View::exists($viewName)) {
            return View::make($viewName, $field->toArray())->render();
        }
        
        return $field->render();
    }

    /**
     * Render a form container.
     */
    public function renderContainer(FormContainer $container): string
    {
        $framework = $container->getFramework();
        $viewName = "form::{$framework}.container";
        
        if (View::exists($viewName)) {
            return View::make($viewName, [
                'container' => $container,
                'forms' => $container->getFormsWithMeta(),
            ])->render();
        }
        
        // Fallback to basic rendering
        return $this->renderContainerBasic($container);
    }

    /**
     * Basic container rendering fallback.
     */
    protected function renderContainerBasic(FormContainer $container): string
    {
        $html = '<div id="' . $container->getId() . '" class="form-container">';
        
        if ($container->getName()) {
            $html .= '<h3 class="form-container-title">' . htmlspecialchars($container->getName()) . '</h3>';
        }
        
        if ($container->getDescription()) {
            $html .= '<p class="form-container-description">' . htmlspecialchars($container->getDescription()) . '</p>';
        }
        
        if ($container->isTabbed()) {
            $html .= $this->renderTabbedContainer($container);
        } elseif ($container->isAccordion()) {
            $html .= $this->renderAccordionContainer($container);
        } else {
            $html .= $this->renderStackedContainer($container);
        }
        
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Render tabbed container.
     */
    protected function renderTabbedContainer(FormContainer $container): string
    {
        $forms = $container->getVisibleForms();
        $activeForm = $container->getActiveForm();
        
        $html = '<div class="nav nav-tabs">';
        
        foreach ($forms as $key => $formData) {
            $isActive = $key === $activeForm;
            $activeClass = $isActive ? ' active' : '';
            
            $html .= '<button class="nav-link' . $activeClass . '" type="button" data-bs-toggle="tab" data-bs-target="#tab-' . $key . '">';
            $html .= htmlspecialchars($formData['title']);
            $html .= '</button>';
        }
        
        $html .= '</div>';
        $html .= '<div class="tab-content">';
        
        foreach ($forms as $key => $formData) {
            $isActive = $key === $activeForm;
            $activeClass = $isActive ? ' show active' : '';
            
            $html .= '<div class="tab-pane fade' . $activeClass . '" id="tab-' . $key . '">';
            $html .= $this->render($formData['form']);
            $html .= '</div>';
        }
        
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Render accordion container.
     */
    protected function renderAccordionContainer(FormContainer $container): string
    {
        $forms = $container->getVisibleForms();
        $activeForm = $container->getActiveForm();
        
        $html = '<div class="accordion">';
        
        foreach ($forms as $key => $formData) {
            $isActive = $key === $activeForm;
            $showClass = $isActive ? ' show' : '';
            $collapsedClass = $isActive ? '' : ' collapsed';
            
            $html .= '<div class="accordion-item">';
            $html .= '<h2 class="accordion-header">';
            $html .= '<button class="accordion-button' . $collapsedClass . '" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-' . $key . '">';
            $html .= htmlspecialchars($formData['title']);
            $html .= '</button>';
            $html .= '</h2>';
            $html .= '<div id="collapse-' . $key . '" class="accordion-collapse collapse' . $showClass . '">';
            $html .= '<div class="accordion-body">';
            $html .= $this->render($formData['form']);
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Render stacked container.
     */
    protected function renderStackedContainer(FormContainer $container): string
    {
        $forms = $container->getVisibleForms();
        
        $html = '<div class="form-stack">';
        
        foreach ($forms as $key => $formData) {
            $html .= '<div class="form-stack-item" data-form-key="' . $key . '">';
            
            if ($formData['title']) {
                $html .= '<h4 class="form-stack-title">' . htmlspecialchars($formData['title']) . '</h4>';
            }
            
            if ($formData['description']) {
                $html .= '<p class="form-stack-description">' . htmlspecialchars($formData['description']) . '</p>';
            }
            
            $html .= $this->render($formData['form']);
            $html .= '</div>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
}
