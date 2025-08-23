/**
 * Litepie Form JavaScript
 * Enhanced form functionality and interactions
 */

(function(window, document) {
    'use strict';

    // Default configuration
    const defaultConfig = {
        csrf_token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        validation: {
            realtime: true,
            debounce: 300
        },
        uploads: {
            max_size: '10MB',
            allowed_mimes: []
        },
        ajax_url: '/'
    };

    // Merge with global configuration
    const config = { ...defaultConfig, ...(window.FormConfig || {}) };

    class LitepieForm {
        constructor() {
            this.forms = new Map();
            this.validators = new Map();
            this.uploaders = new Map();
            
            this.init();
        }

        init() {
            document.addEventListener('DOMContentLoaded', () => {
                this.initializeForms();
                this.bindEvents();
            });
        }

        initializeForms() {
            document.querySelectorAll('.litepie-form').forEach(form => {
                this.setupForm(form);
            });
        }

        setupForm(form) {
            const formId = form.id || 'form_' + Math.random().toString(36).substr(2, 9);
            form.id = formId;

            this.forms.set(formId, {
                element: form,
                fields: new Map(),
                validators: new Map(),
                config: this.getFormConfig(form)
            });

            this.setupValidation(form);
            this.setupConditionalLogic(form);
            this.setupFileUploads(form);
            this.setupMultiStep(form);
        }

        getFormConfig(form) {
            const configScript = form.querySelector('script[type="application/json"]');
            if (configScript) {
                try {
                    return JSON.parse(configScript.textContent);
                } catch (e) {
                    console.warn('Invalid form configuration JSON:', e);
                }
            }
            return {};
        }

        setupValidation(form) {
            if (!config.validation.realtime) return;

            const fields = form.querySelectorAll('[data-validation]');
            fields.forEach(field => {
                this.setupFieldValidation(field, form);
            });
        }

        setupFieldValidation(field, form) {
            let timeout;
            const validate = () => {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    this.validateField(field, form);
                }, config.validation.debounce);
            };

            field.addEventListener('input', validate);
            field.addEventListener('blur', () => this.validateField(field, form));
        }

        async validateField(field, form) {
            const rules = field.dataset.validation;
            if (!rules) return;

            const value = field.value;
            const name = field.name;

            try {
                const response = await fetch(config.ajax_url + '/validate-field', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': config.csrf_token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        field: name,
                        value: value,
                        rules: rules
                    })
                });

                const result = await response.json();
                this.updateFieldValidation(field, result);
            } catch (error) {
                console.error('Validation error:', error);
            }
        }

        updateFieldValidation(field, result) {
            const wrapper = field.closest('.mb-3') || field.parentElement;
            const existingFeedback = wrapper.querySelector('.invalid-feedback');

            // Remove existing validation classes and feedback
            field.classList.remove('is-valid', 'is-invalid');
            if (existingFeedback) {
                existingFeedback.remove();
            }

            if (result.valid) {
                field.classList.add('is-valid');
            } else {
                field.classList.add('is-invalid');
                
                if (result.errors && result.errors.length > 0) {
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.innerHTML = result.errors.map(error => `<div>${error}</div>`).join('');
                    field.parentElement.appendChild(feedback);
                }
            }
        }

        setupConditionalLogic(form) {
            const conditionalFields = form.querySelectorAll('[data-show-if], [data-hide-if]');
            
            conditionalFields.forEach(field => {
                this.setupConditionalField(field, form);
            });
        }

        setupConditionalField(field, form) {
            const showIf = field.dataset.showIf;
            const hideIf = field.dataset.hideIf;
            
            const checkConditions = () => {
                let shouldShow = true;
                
                if (showIf) {
                    shouldShow = this.evaluateCondition(showIf, form);
                }
                
                if (hideIf && shouldShow) {
                    shouldShow = !this.evaluateCondition(hideIf, form);
                }
                
                this.toggleField(field, shouldShow);
            };

            // Initial check
            checkConditions();

            // Listen for changes on dependent fields
            const dependencies = this.extractDependencies(showIf || hideIf);
            dependencies.forEach(dep => {
                const depField = form.querySelector(`[name="${dep}"]`);
                if (depField) {
                    depField.addEventListener('change', checkConditions);
                }
            });
        }

        evaluateCondition(condition, form) {
            // Simple condition parsing: field:value or field:!value
            const [fieldName, expectedValue] = condition.split(':');
            const field = form.querySelector(`[name="${fieldName}"]`);
            
            if (!field) return false;
            
            let fieldValue = field.value;
            
            if (field.type === 'checkbox' || field.type === 'radio') {
                fieldValue = field.checked ? field.value : '';
            }
            
            if (expectedValue.startsWith('!')) {
                return fieldValue !== expectedValue.substring(1);
            }
            
            return fieldValue === expectedValue;
        }

        extractDependencies(condition) {
            // Extract field names from condition string
            const matches = condition.match(/(\w+):/g);
            return matches ? matches.map(match => match.replace(':', '')) : [];
        }

        toggleField(field, show) {
            const wrapper = field.closest('.mb-3') || field.parentElement;
            
            if (show) {
                wrapper.style.display = '';
                wrapper.classList.remove('field-conditional', 'hidden');
                field.disabled = false;
            } else {
                wrapper.style.display = 'none';
                wrapper.classList.add('field-conditional', 'hidden');
                field.disabled = true;
            }
        }

        setupFileUploads(form) {
            const fileInputs = form.querySelectorAll('input[type="file"]');
            
            fileInputs.forEach(input => {
                this.setupFileUpload(input);
            });
        }

        setupFileUpload(input) {
            const wrapper = this.createFileUploadWrapper(input);
            input.parentElement.appendChild(wrapper);
            
            this.setupDragAndDrop(wrapper, input);
            this.setupFilePreview(input);
        }

        createFileUploadWrapper(input) {
            const wrapper = document.createElement('div');
            wrapper.className = 'file-upload-area';
            wrapper.innerHTML = `
                <div class="file-upload-content">
                    <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                    <p>Drop files here or click to select</p>
                </div>
            `;
            
            wrapper.addEventListener('click', () => input.click());
            
            return wrapper;
        }

        setupDragAndDrop(wrapper, input) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                wrapper.addEventListener(eventName, this.preventDefaults, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                wrapper.addEventListener(eventName, () => wrapper.classList.add('dragover'), false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                wrapper.addEventListener(eventName, () => wrapper.classList.remove('dragover'), false);
            });

            wrapper.addEventListener('drop', (e) => {
                const files = e.dataTransfer.files;
                input.files = files;
                this.handleFiles(files, input);
            });
        }

        setupFilePreview(input) {
            input.addEventListener('change', (e) => {
                this.handleFiles(e.target.files, input);
            });
        }

        handleFiles(files, input) {
            const previewContainer = this.getOrCreatePreviewContainer(input);
            previewContainer.innerHTML = '';

            Array.from(files).forEach(file => {
                const preview = this.createFilePreview(file);
                previewContainer.appendChild(preview);
            });
        }

        getOrCreatePreviewContainer(input) {
            let container = input.parentElement.querySelector('.file-preview');
            if (!container) {
                container = document.createElement('div');
                container.className = 'file-preview';
                input.parentElement.appendChild(container);
            }
            return container;
        }

        createFilePreview(file) {
            const preview = document.createElement('div');
            preview.className = 'file-preview-item';
            
            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.style.maxWidth = '100px';
                img.style.maxHeight = '100px';
                img.file = file;
                
                const reader = new FileReader();
                reader.onload = (e) => img.src = e.target.result;
                reader.readAsDataURL(file);
                
                preview.appendChild(img);
            } else {
                preview.innerHTML = `<span>${file.name}</span>`;
            }
            
            const removeBtn = document.createElement('button');
            removeBtn.className = 'file-preview-remove';
            removeBtn.innerHTML = '×';
            removeBtn.onclick = () => preview.remove();
            
            preview.appendChild(removeBtn);
            
            return preview;
        }

        setupMultiStep(form) {
            const steps = form.querySelectorAll('.form-step-content');
            if (steps.length <= 1) return;

            let currentStep = 0;
            
            this.updateStepDisplay(steps, currentStep);
            this.setupStepNavigation(form, steps, currentStep);
        }

        updateStepDisplay(steps, currentStep) {
            steps.forEach((step, index) => {
                step.style.display = index === currentStep ? 'block' : 'none';
            });
            
            // Update step indicators
            const indicators = document.querySelectorAll('.form-step');
            indicators.forEach((indicator, index) => {
                indicator.classList.remove('active', 'completed');
                if (index < currentStep) {
                    indicator.classList.add('completed');
                } else if (index === currentStep) {
                    indicator.classList.add('active');
                }
            });
        }

        setupStepNavigation(form, steps, currentStep) {
            const nextBtns = form.querySelectorAll('.btn-next');
            const prevBtns = form.querySelectorAll('.btn-prev');
            
            nextBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    if (currentStep < steps.length - 1) {
                        currentStep++;
                        this.updateStepDisplay(steps, currentStep);
                    }
                });
            });
            
            prevBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    if (currentStep > 0) {
                        currentStep--;
                        this.updateStepDisplay(steps, currentStep);
                    }
                });
            });
        }

        preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        bindEvents() {
            // Global form submission handler
            document.addEventListener('submit', (e) => {
                if (e.target.classList.contains('litepie-form')) {
                    this.handleFormSubmission(e);
                }
            });
        }

        async handleFormSubmission(e) {
            const form = e.target;
            const formData = new FormData(form);
            
            // Add loading state
            form.classList.add('loading');
            
            try {
                const response = await fetch(form.action, {
                    method: form.method,
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': config.csrf_token,
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.handleSuccessResponse(form, result);
                } else {
                    this.handleErrorResponse(form, result);
                }
            } catch (error) {
                console.error('Form submission error:', error);
                this.handleErrorResponse(form, { errors: { general: ['An error occurred. Please try again.'] } });
            } finally {
                form.classList.remove('loading');
            }
        }

        handleSuccessResponse(form, result) {
            // Clear form if specified
            if (result.reset) {
                form.reset();
            }
            
            // Show success message
            if (result.message) {
                this.showMessage(result.message, 'success');
            }
            
            // Redirect if specified
            if (result.redirect) {
                window.location.href = result.redirect;
            }
        }

        handleErrorResponse(form, result) {
            // Clear existing errors
            form.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            
            form.querySelectorAll('.invalid-feedback').forEach(feedback => {
                feedback.remove();
            });
            
            // Display field errors
            if (result.errors) {
                Object.keys(result.errors).forEach(fieldName => {
                    const field = form.querySelector(`[name="${fieldName}"]`);
                    if (field) {
                        this.updateFieldValidation(field, {
                            valid: false,
                            errors: result.errors[fieldName]
                        });
                    }
                });
            }
            
            // Show general error message
            if (result.message) {
                this.showMessage(result.message, 'error');
            }
        }

        showMessage(message, type = 'info') {
            // Simple toast notification
            const toast = document.createElement('div');
            toast.className = `alert alert-${type === 'error' ? 'danger' : type} toast`;
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 300px;
                opacity: 0;
                transition: opacity 0.3s;
            `;
            toast.innerHTML = `
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
                ${message}
            `;
            
            document.body.appendChild(toast);
            
            // Animate in
            setTimeout(() => toast.style.opacity = '1', 10);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            window.LitepieForm = new LitepieForm();
        });
    } else {
        window.LitepieForm = new LitepieForm();
    }

})(window, document);
