{{-- Tailwind CSS Password Field --}}
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
        $showStrengthMeter = $field->getAttribute('showStrengthMeter', true);
        $showToggle = $field->getAttribute('showToggle', true);
        $showRequirements = $field->getAttribute('showRequirements', true);
        $minLength = $field->getAttribute('minlength', 8);
        $requireUppercase = $field->getAttribute('requireUppercase', true);
        $requireLowercase = $field->getAttribute('requireLowercase', true);
        $requireNumbers = $field->getAttribute('requireNumbers', true);
        $requireSpecial = $field->getAttribute('requireSpecial', true);
    @endphp

    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
            </svg>
        </div>

        <input type="password" 
               name="{{ $field->getName() }}" 
               id="{{ $field->getId() }}"
               value="{{ $value }}"
               class="block w-full pl-10 pr-12 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm
                      {{ $field->hasErrors() ? 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500' : '' }}
                      {{ $field->getClass() }}"
               placeholder="{{ $field->getAttribute('placeholder', 'Enter your password') }}"
               @if($field->isRequired()) required @endif
               @if($field->isDisabled()) disabled @endif
               @if($minLength) minlength="{{ $minLength }}" @endif
               @if($showStrengthMeter || $showRequirements) oninput="validatePassword('{{ $field->getId() }}')" @endif
               {!! $field->getAttributesString() !!}>

        @if($showToggle)
            <button type="button" 
                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                    onclick="togglePasswordVisibility('{{ $field->getId() }}')"
                    tabindex="-1">
                <svg class="h-5 w-5 text-gray-400 hover:text-gray-600 show-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                </svg>
                <svg class="h-5 w-5 text-gray-400 hover:text-gray-600 hide-icon hidden" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />
                    <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                </svg>
            </button>
        @endif

        @if($field->hasErrors())
            <div class="absolute inset-y-0 right-0 pr-{{ $showToggle ? '10' : '3' }} flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
        @endif
    </div>

    @if($showStrengthMeter)
        <div class="mt-2">
            <div class="flex items-center space-x-2">
                <span class="text-xs text-gray-500">Strength:</span>
                <div class="flex-1 bg-gray-200 rounded-full h-2">
                    <div class="h-2 rounded-full transition-all duration-300" 
                         id="{{ $field->getId() }}_strength_bar"></div>
                </div>
                <span class="text-xs font-medium" id="{{ $field->getId() }}_strength_text">-</span>
            </div>
        </div>
    @endif

    @if($showRequirements)
        <div class="mt-2 space-y-1" id="{{ $field->getId() }}_requirements">
            <div class="text-xs text-gray-600">Password must contain:</div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-1 text-xs">
                @if($minLength)
                    <div class="flex items-center space-x-1" data-requirement="length">
                        <svg class="h-3 w-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-500">At least {{ $minLength }} characters</span>
                    </div>
                @endif
                
                @if($requireUppercase)
                    <div class="flex items-center space-x-1" data-requirement="uppercase">
                        <svg class="h-3 w-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-500">One uppercase letter</span>
                    </div>
                @endif
                
                @if($requireLowercase)
                    <div class="flex items-center space-x-1" data-requirement="lowercase">
                        <svg class="h-3 w-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-500">One lowercase letter</span>
                    </div>
                @endif
                
                @if($requireNumbers)
                    <div class="flex items-center space-x-1" data-requirement="numbers">
                        <svg class="h-3 w-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-500">One number</span>
                    </div>
                @endif
                
                @if($requireSpecial)
                    <div class="flex items-center space-x-1" data-requirement="special">
                        <svg class="h-3 w-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-500">One special character</span>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if($field->hasErrors())
        <p class="text-sm text-red-600">{{ $field->getFirstError() }}</p>
    @endif

    @if($field->getHelp())
        <p class="text-sm text-gray-500">{{ $field->getHelp() }}</p>
    @endif
</div>

<script>
function togglePasswordVisibility(fieldId) {
    const input = document.getElementById(fieldId);
    const showIcon = input.parentElement.querySelector('.show-icon');
    const hideIcon = input.parentElement.querySelector('.hide-icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        showIcon.classList.add('hidden');
        hideIcon.classList.remove('hidden');
    } else {
        input.type = 'password';
        showIcon.classList.remove('hidden');
        hideIcon.classList.add('hidden');
    }
}

function validatePassword(fieldId) {
    const input = document.getElementById(fieldId);
    const password = input.value;
    
    // Requirements validation
    const requirementsContainer = document.getElementById(fieldId + '_requirements');
    if (requirementsContainer) {
        const requirements = {
            length: password.length >= {{ $minLength }},
            uppercase: {{ $requireUppercase ? '/[A-Z]/.test(password)' : 'true' }},
            lowercase: {{ $requireLowercase ? '/[a-z]/.test(password)' : 'true' }},
            numbers: {{ $requireNumbers ? '/[0-9]/.test(password)' : 'true' }},
            special: {{ $requireSpecial ? '/[^A-Za-z0-9]/.test(password)' : 'true' }}
        };
        
        Object.keys(requirements).forEach(req => {
            const element = requirementsContainer.querySelector(`[data-requirement="${req}"]`);
            if (element) {
                const icon = element.querySelector('svg');
                const text = element.querySelector('span');
                
                if (requirements[req]) {
                    icon.classList.remove('text-gray-300');
                    icon.classList.add('text-green-500');
                    text.classList.remove('text-gray-500');
                    text.classList.add('text-green-600');
                } else {
                    icon.classList.remove('text-green-500');
                    icon.classList.add('text-gray-300');
                    text.classList.remove('text-green-600');
                    text.classList.add('text-gray-500');
                }
            }
        });
    }
    
    // Strength meter
    const strengthBar = document.getElementById(fieldId + '_strength_bar');
    const strengthText = document.getElementById(fieldId + '_strength_text');
    
    if (strengthBar && strengthText) {
        let score = 0;
        const checks = [
            password.length >= 8,
            password.length >= 12,
            /[a-z]/.test(password),
            /[A-Z]/.test(password),
            /[0-9]/.test(password),
            /[^A-Za-z0-9]/.test(password)
        ];
        
        score = checks.reduce((acc, check) => acc + (check ? 1 : 0), 0);
        
        let strength, color, width;
        
        if (score <= 2) {
            strength = 'Weak';
            color = 'bg-red-500';
            width = '25%';
        } else if (score <= 4) {
            strength = 'Fair';
            color = 'bg-yellow-500';
            width = '50%';
        } else if (score <= 5) {
            strength = 'Good';
            color = 'bg-blue-500';
            width = '75%';
        } else {
            strength = 'Strong';
            color = 'bg-green-500';
            width = '100%';
        }
        
        strengthBar.className = `h-2 rounded-full transition-all duration-300 ${color}`;
        strengthBar.style.width = password ? width : '0%';
        strengthText.textContent = password ? strength : '-';
        strengthText.className = `text-xs font-medium ${password ? (color.replace('bg-', 'text-').replace('-500', '-600')) : 'text-gray-400'}`;
    }
}

// Initialize password validation on page load
document.addEventListener('DOMContentLoaded', function() {
    const passwordFields = document.querySelectorAll('input[type="password"]');
    passwordFields.forEach(input => {
        if (input.value) {
            validatePassword(input.id);
        }
    });
});
</script>
