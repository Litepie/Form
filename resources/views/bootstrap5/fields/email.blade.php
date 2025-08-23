{{-- Bootstrap 5 Email Field --}}
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
        <span class="input-group-text">
            <i class="bi bi-envelope"></i>
        </span>
        <input type="email" 
               name="{{ $field->getName() }}" 
               id="{{ $field->getId() }}"
               value="{{ old($field->getName(), $field->getValue()) }}"
               class="form-control {{ $field->hasErrors() ? 'is-invalid' : '' }} {{ $field->getClass() }}"
               @if($field->getPlaceholder()) placeholder="{{ $field->getPlaceholder() }}" @endif
               @if($field->isRequired()) required @endif
               @if($field->isDisabled()) disabled @endif
               @if($field->isReadonly()) readonly @endif
               {!! $field->getAttributesString() !!}>
        
        @if($field->hasErrors())
            <div class="invalid-feedback">
                {{ $field->getFirstError() }}
            </div>
        @endif
    </div>

    @if($field->getHelp())
        <div class="form-text">{{ $field->getHelp() }}</div>
    @endif
</div>
