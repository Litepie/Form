{{-- Tailwind CSS Email Field --}}
<div class="space-y-1 {{ $field->getWrapperClass() }}" {!! $field->getWrapperAttributes() !!}>
    @if($field->getLabel())
        <label for="{{ $field->getId() }}" class="block text-sm font-medium text-gray-700">
            {{ $field->getLabel() }}
            @if($field->isRequired())
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    @php
        $value = old($field->getName(), $field->getValue());
        $validateDomain = $field->getAttribute('validateDomain', false);
        $allowedDomains = $field->getAttribute('allowedDomains', []);
    @endphp

    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
            </svg>
        </div>

        <input type="email" 
               name="{{ $field->getName() }}" 
               id="{{ $field->getId() }}"
               value="{{ $value }}"
               class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm
                      {{ $field->hasErrors() ? 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500' : '' }}
                      {{ $field->getClass() }}"
               placeholder="{{ $field->getAttribute('placeholder', 'Enter your email address') }}"
               @if($field->isRequired()) required @endif
               @if($field->isDisabled()) disabled @endif
               @if($validateDomain) onblur="validateEmailDomain('{{ $field->getId() }}')" @endif
               {!! $field->getAttributesString() !!}>

        @if($field->hasErrors())
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
        @endif

        {{-- Email validation indicator --}}
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center" 
             id="{{ $field->getId() }}_indicator" 
             style="display: none;">
            <div class="valid-indicator hidden">
                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="invalid-indicator hidden">
                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
    </div>

    @if($field->hasErrors())
        <p class="text-sm text-red-600">{{ $field->getFirstError() }}</p>
    @endif

    @if($allowedDomains)
        <div class="text-xs text-gray-500">
            Allowed domains: {{ implode(', ', $allowedDomains) }}
        </div>
    @endif

    @if($field->getHelp())
        <p class="text-sm text-gray-500">{{ $field->getHelp() }}</p>
    @endif
</div>

<script>
function validateEmailDomain(fieldId) {
    const input = document.getElementById(fieldId);
    const indicator = document.getElementById(fieldId + '_indicator');
    const validIcon = indicator?.querySelector('.valid-indicator');
    const invalidIcon = indicator?.querySelector('.invalid-indicator');
    const allowedDomains = @json($allowedDomains);
    
    if (!input.value) {
        indicator.style.display = 'none';
        return;
    }
    
    const email = input.value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    let isValid = emailRegex.test(email);
    
    // Check domain if specified
    if (isValid && allowedDomains.length > 0) {
        const domain = email.split('@')[1]?.toLowerCase();
        isValid = allowedDomains.includes(domain);
    }
    
    if (indicator && validIcon && invalidIcon) {
        indicator.style.display = 'flex';
        
        if (isValid) {
            validIcon.classList.remove('hidden');
            invalidIcon.classList.add('hidden');
            input.setCustomValidity('');
        } else {
            validIcon.classList.add('hidden');
            invalidIcon.classList.remove('hidden');
            
            if (allowedDomains.length > 0) {
                input.setCustomValidity('Email domain not allowed. Allowed domains: ' + allowedDomains.join(', '));
            } else {
                input.setCustomValidity('Please enter a valid email address');
            }
        }
    }
}

// Real-time validation
document.addEventListener('DOMContentLoaded', function() {
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('input', function() {
            setTimeout(() => validateEmailDomain(this.id), 500);
        });
        
        // Initial validation if has value
        if (input.value) {
            validateEmailDomain(input.id);
        }
    });
});
</script>
