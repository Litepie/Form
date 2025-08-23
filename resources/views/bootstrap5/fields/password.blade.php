{{-- Bootstrap 5 Password Field --}}
<div class="mb-3 {{ $field->getWrapperClass() }}" {!! $field->getWrapperAttributes() !!}>
    @if($field->getLabel())
        <label for="{{ $field->getId() }}" class="form-label">
            {{ $field->getLabel() }}
            @if($field->isRequired())
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <div class="input-group">
        <input type="password" 
               name="{{ $field->getName() }}" 
               id="{{ $field->getId() }}"
               value="{{ old($field->getName(), $field->getValue()) }}"
               class="form-control {{ $field->hasErrors() ? 'is-invalid' : '' }} {{ $field->getClass() }}"
               @if($field->getPlaceholder()) placeholder="{{ $field->getPlaceholder() }}" @endif
               @if($field->isRequired()) required @endif
               @if($field->isDisabled()) disabled @endif
               @if($field->isReadonly()) readonly @endif
               autocomplete="new-password"
               {!! $field->getAttributesString() !!}>
        
        @if($field->getAttribute('showToggle', true))
            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('{{ $field->getId() }}')">
                <i class="bi bi-eye" id="{{ $field->getId() }}_toggle_icon"></i>
            </button>
        @endif
        
        @if($field->hasErrors())
            <div class="invalid-feedback">
                {{ $field->getFirstError() }}
            </div>
        @endif
    </div>

    @if($field->getAttribute('showStrength', true))
        <div class="password-strength mt-2">
            <div class="progress" style="height: 4px;">
                <div id="{{ $field->getId() }}_strength" class="progress-bar" role="progressbar" style="width: 0%"></div>
            </div>
            <small id="{{ $field->getId() }}_strength_text" class="form-text text-muted">Password strength: Weak</small>
        </div>
    @endif

    @if($field->getHelp())
        <div class="form-text">{{ $field->getHelp() }}</div>
    @endif

    @if($field->getAttribute('showRequirements', true))
        <div class="password-requirements mt-2">
            <small class="form-text text-muted">
                Password must contain:
                <ul class="mb-0">
                    <li id="{{ $field->getId() }}_length" class="text-muted">At least 8 characters</li>
                    <li id="{{ $field->getId() }}_uppercase" class="text-muted">One uppercase letter</li>
                    <li id="{{ $field->getId() }}_lowercase" class="text-muted">One lowercase letter</li>
                    <li id="{{ $field->getId() }}_number" class="text-muted">One number</li>
                    <li id="{{ $field->getId() }}_special" class="text-muted">One special character</li>
                </ul>
            </small>
        </div>
    @endif
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '_toggle_icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        field.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
