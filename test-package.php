<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

// Test if the form package classes can be loaded
try {
    $classes = [
        'Litepie\Form\FormBuilder',
        'Litepie\Form\FormContainer',
        'Litepie\Form\FormManager',
        'Litepie\Form\Commands\InstallCommand',
        'Litepie\Form\Commands\MakeFormCommand',
        'Litepie\Form\Commands\PublishCommand',
    ];
    
    echo "Testing Litepie Form Package Classes:\n";
    echo "====================================\n\n";
    
    foreach ($classes as $class) {
        if (class_exists($class)) {
            echo "✅ {$class} - FOUND\n";
        } else {
            echo "❌ {$class} - NOT FOUND\n";
        }
    }
    
    echo "\nTesting helpers:\n";
    echo "===============\n";
    
    if (function_exists('form_create')) {
        echo "✅ form_create() - FOUND\n";
    } else {
        echo "❌ form_create() - NOT FOUND\n";
    }
    
    if (function_exists('form_container')) {
        echo "✅ form_container() - FOUND\n";
    } else {
        echo "❌ form_container() - NOT FOUND\n";
    }
    
    echo "\nPackage structure test completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
