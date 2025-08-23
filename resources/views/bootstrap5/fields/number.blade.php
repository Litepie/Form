{{-- Bootstrap 5 Number Field --}}
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
        @if($field->getAttribute('prefix'))
            <span class="input-group-text">{{ $field->getAttribute('prefix') }}</span>
        @endif
        
        <input type="number" 
               name="{{ $field->getName() }}" 
               id="{{ $field->getId() }}"
               value="{{ old($field->getName(), $field->getValue()) }}"
               class="form-control {{ $field->hasErrors() ? 'is-invalid' : '' }} {{ $field->getClass() }}"
               @if($field->getPlaceholder()) placeholder="{{ $field->getPlaceholder() }}" @endif
               @if($field->getAttribute('min')) min="{{ $field->getAttribute('min') }}" @endif
               @if($field->getAttribute('max')) max="{{ $field->getAttribute('max') }}" @endif
               @if($field->getAttribute('step')) step="{{ $field->getAttribute('step') }}" @endif
               @if($field->isRequired()) required @endif
               @if($field->isDisabled()) disabled @endif
               @if($field->isReadonly()) readonly @endif
               {!! $field->getAttributesString() !!}>
        
        @if($field->getAttribute('suffix'))
            <span class="input-group-text">{{ $field->getAttribute('suffix') }}</span>
        @endif
        
        @if($field->hasErrors())
            <div class="invalid-feedback">
                {{ $field->getFirstError() }}
            </div>
        @endif
    </div>

    @if($field->getAttribute('min') || $field->getAttribute('max'))
        <div class="form-text">
            @if($field->getAttribute('min') && $field->getAttribute('max'))
                Value must be between {{ $field->getAttribute('min') }} and {{ $field->getAttribute('max') }}.
            @elseif($field->getAttribute('min'))
                Minimum value: {{ $field->getAttribute('min') }}.
            @elseif($field->getAttribute('max'))
                Maximum value: {{ $field->getAttribute('max') }}.
            @endif
        </div>
    @endif

    @if($field->getHelp())
        <div class="form-text">{{ $field->getHelp() }}</div>
    @endif
</div>
