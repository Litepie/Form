{{-- Bootstrap 5 Form Layout --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Form' }}</title>
    
    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    {{-- Custom Form Styles --}}
    <style>
        .form-wrapper {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        
        .step-indicator .step {
            flex: 1;
            text-align: center;
            position: relative;
        }
        
        .step-indicator .step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 15px;
            right: -50%;
            width: 100%;
            height: 2px;
            background: #dee2e6;
            z-index: 1;
        }
        
        .step-indicator .step.completed::after {
            background: #198754;
        }
        
        .step-indicator .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #dee2e6;
            color: #6c757d;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
            margin-bottom: 0.5rem;
        }
        
        .step-indicator .step.active .step-number {
            background: #0d6efd;
            color: white;
        }
        
        .step-indicator .step.completed .step-number {
            background: #198754;
            color: white;
        }
        
        .form-section {
            display: none;
        }
        
        .form-section.active {
            display: block;
        }
        
        .form-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #dee2e6;
        }
        
        .field-group {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .field-group .field-group-title {
            font-weight: 600;
            color: #495057;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #dee2e6;
        }
        
        .form-errors {
            background: #f8d7da;
            border: 1px solid #f5c2c7;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .form-success {
            background: #d1e7dd;
            border: 1px solid #badbcc;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .required-indicator {
            color: #dc3545;
            font-weight: bold;
        }
        
        .form-loading {
            position: relative;
        }
        
        .form-loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        
        .conditional-field {
            transition: all 0.3s ease;
        }
        
        .conditional-field.hidden {
            display: none;
        }
        
        @media (max-width: 768px) {
            .form-wrapper {
                padding: 1rem;
            }
            
            .step-indicator {
                flex-direction: column;
                gap: 1rem;
            }
            
            .step-indicator .step:not(:last-child)::after {
                display: none;
            }
            
            .form-navigation {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="form-wrapper">
            @yield('content')
        </div>
    </div>
    
    {{-- Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- Form Enhancement Scripts --}}
    <script>
        // Form validation enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form[data-validate="true"]');
            
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!form.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Focus first invalid field
                        const firstInvalid = form.querySelector(':invalid');
                        if (firstInvalid) {
                            firstInvalid.focus();
                        }
                    }
                    
                    form.classList.add('was-validated');
                });
            });
        });
        
        // Auto-save functionality
        function enableAutoSave(formId, saveUrl, interval = 30000) {
            const form = document.getElementById(formId);
            if (!form) return;
            
            let saveTimer;
            
            function saveFormData() {
                const formData = new FormData(form);
                
                fetch(saveUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Form auto-saved:', data);
                })
                .catch(error => {
                    console.error('Auto-save failed:', error);
                });
            }
            
            form.addEventListener('input', function() {
                clearTimeout(saveTimer);
                saveTimer = setTimeout(saveFormData, interval);
            });
        }
        
        // Conditional fields
        function toggleConditionalFields() {
            const conditionalFields = document.querySelectorAll('[data-conditional]');
            
            conditionalFields.forEach(field => {
                const condition = JSON.parse(field.dataset.conditional);
                const triggerField = document.querySelector(`[name="${condition.field}"]`);
                
                if (triggerField) {
                    function checkCondition() {
                        let triggerValue;
                        
                        if (triggerField.type === 'checkbox') {
                            triggerValue = triggerField.checked;
                        } else if (triggerField.type === 'radio') {
                            const checked = document.querySelector(`[name="${condition.field}"]:checked`);
                            triggerValue = checked ? checked.value : null;
                        } else {
                            triggerValue = triggerField.value;
                        }
                        
                        let show = false;
                        
                        switch (condition.operator) {
                            case 'equals':
                                show = triggerValue == condition.value;
                                break;
                            case 'not_equals':
                                show = triggerValue != condition.value;
                                break;
                            case 'contains':
                                show = triggerValue && triggerValue.toString().includes(condition.value);
                                break;
                            case 'greater_than':
                                show = parseFloat(triggerValue) > parseFloat(condition.value);
                                break;
                            case 'less_than':
                                show = parseFloat(triggerValue) < parseFloat(condition.value);
                                break;
                            case 'is_empty':
                                show = !triggerValue || triggerValue.toString().trim() === '';
                                break;
                            case 'is_not_empty':
                                show = triggerValue && triggerValue.toString().trim() !== '';
                                break;
                        }
                        
                        field.classList.toggle('hidden', !show);
                        
                        // Disable/enable fields inside hidden containers
                        const inputs = field.querySelectorAll('input, select, textarea');
                        inputs.forEach(input => {
                            input.disabled = !show;
                        });
                    }
                    
                    triggerField.addEventListener('change', checkCondition);
                    triggerField.addEventListener('input', checkCondition);
                    
                    // Initial check
                    checkCondition();
                }
            });
        }
        
        // Initialize conditional fields on page load
        document.addEventListener('DOMContentLoaded', toggleConditionalFields);
        
        // Form progress tracking
        function updateFormProgress() {
            const form = document.querySelector('form[data-track-progress]');
            if (!form) return;
            
            const requiredFields = form.querySelectorAll('[required]');
            const filledFields = Array.from(requiredFields).filter(field => {
                if (field.type === 'checkbox' || field.type === 'radio') {
                    return field.checked;
                }
                return field.value.trim() !== '';
            });
            
            const progress = Math.round((filledFields.length / requiredFields.length) * 100);
            const progressBar = document.querySelector('.form-progress-bar');
            
            if (progressBar) {
                progressBar.style.width = progress + '%';
                progressBar.textContent = progress + '%';
            }
        }
        
        // Update progress on input
        document.addEventListener('input', updateFormProgress);
        document.addEventListener('change', updateFormProgress);
    </script>
    
    @stack('scripts')
</body>
</html>
