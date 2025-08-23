{{-- Bootstrap 5 Radio Field --}}
<div class="mb-3 {{ $field->getWrapperClass() }}" {!! $field->getWrapperAttributes() !!}>
    @if($field->getLabel())
        <label class="form-label">
            {{ $field->getLabel() }}
            @if($field->isRequired())
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    @php
        $selectedValue = old($field->getName(), $field->getValue());
        $inline = $field->getAttribute('inline', false);
        $containerClass = $inline ? 'd-flex flex-wrap gap-3' : '';
        $itemClass = $inline ? 'form-check-inline' : 'form-check';
    @endphp

    <div class="{{ $containerClass }}">
        @foreach($field->getOptions() as $value => $label)
            <div class="{{ $itemClass }}">
                <input type="radio" 
                       name="{{ $field->getName() }}" 
                       id="{{ $field->getId() }}_{{ $loop->index }}"
                       value="{{ $value }}"
                       class="form-check-input {{ $field->hasErrors() ? 'is-invalid' : '' }}"
                       @if($selectedValue == $value) checked @endif
                       @if($field->isRequired()) required @endif
                       @if($field->isDisabled()) disabled @endif
                       {!! $field->getAttributesString() !!}>
                
                <label class="form-check-label" for="{{ $field->getId() }}_{{ $loop->index }}">
                    {{ $label }}
                </label>
            </div>
        @endforeach
    </div>

    @if($field->getHelp())
        <div class="form-text">{{ $field->getHelp() }}</div>
    @endif

    @if($field->hasErrors())
        <div class="invalid-feedback d-block">
            {{ $field->getFirstError() }}
        </div>
    @endif
</div>
