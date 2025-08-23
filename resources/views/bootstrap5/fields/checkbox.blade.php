{{-- Bootstrap 5 Checkbox Field --}}
@php
    $options = $field->getOptions();
    $selectedValues = old($field->getName(), $field->getValue()) ?: [];
    $selectedValues = is_array($selectedValues) ? $selectedValues : [$selectedValues];
    $inline = $field->getAttribute('inline', false);
    $containerClass = $inline ? 'd-flex flex-wrap gap-3' : '';
    $itemClass = $inline ? 'form-check-inline' : 'form-check';
@endphp

<div class="mb-3 {{ $field->getWrapperClass() }}" {!! $field->getWrapperAttributes() !!}>
    @if($field->getLabel())
        <label class="form-label">
            {{ $field->getLabel() }}
            @if($field->isRequired())
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    @if(empty($options))
        {{-- Single checkbox --}}
        <div class="form-check">
            <input type="hidden" name="{{ $field->getName() }}" value="0">
            <input type="checkbox" 
                   name="{{ $field->getName() }}" 
                   id="{{ $field->getId() }}"
                   value="{{ $field->getAttribute('value', 1) }}"
                   class="form-check-input {{ $field->hasErrors() ? 'is-invalid' : '' }}"
                   @if(in_array($field->getAttribute('value', 1), $selectedValues)) checked @endif
                   @if($field->isRequired()) required @endif
                   @if($field->isDisabled()) disabled @endif
                   {!! $field->getAttributesString() !!}>
            
            <label class="form-check-label" for="{{ $field->getId() }}">
                {{ $field->getAttribute('checkboxLabel', $field->getLabel()) }}
            </label>
        </div>
    @else
        {{-- Multiple checkboxes --}}
        <div class="{{ $containerClass }}">
            @foreach($options as $value => $label)
                <div class="{{ $itemClass }}">
                    <input type="checkbox" 
                           name="{{ $field->getName() }}[]" 
                           id="{{ $field->getId() }}_{{ $loop->index }}"
                           value="{{ $value }}"
                           class="form-check-input {{ $field->hasErrors() ? 'is-invalid' : '' }}"
                           @if(in_array($value, $selectedValues)) checked @endif
                           @if($field->isRequired()) required @endif
                           @if($field->isDisabled()) disabled @endif
                           {!! $field->getAttributesString() !!}>
                    
                    <label class="form-check-label" for="{{ $field->getId() }}_{{ $loop->index }}">
                        {{ $label }}
                    </label>
                </div>
            @endforeach
        </div>

        @if($field->getAttribute('selectAll', false))
            <div class="form-check mt-2">
                <input type="checkbox" 
                       id="{{ $field->getId() }}_select_all"
                       class="form-check-input"
                       onchange="toggleAllCheckboxes('{{ $field->getName() }}', this.checked)">
                <label class="form-check-label" for="{{ $field->getId() }}_select_all">
                    Select All
                </label>
            </div>
            <script>
                function toggleAllCheckboxes(name, checked) {
                    const checkboxes = document.querySelectorAll(`input[name="${name}[]"]`);
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = checked;
                    });
                }
            </script>
        @endif
    @endif

    @if($field->getHelp())
        <div class="form-text">{{ $field->getHelp() }}</div>
    @endif

    @if($field->hasErrors())
        <div class="invalid-feedback d-block">
            {{ $field->getFirstError() }}
        </div>
    @endif
</div>
