{{-- Bootstrap 5 Select Field --}}
<div class="mb-3 {{ $field->getWrapperClass() }}" {!! $field->getWrapperAttributes() !!}>
    @if($field->getLabel())
        <label for="{{ $field->getId() }}" class="form-label">
            {{ $field->getLabel() }}
            @if($field->isRequired())
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <select name="{{ $field->getName() }}{{ $field->getAttribute('multiple') ? '[]' : '' }}" 
            id="{{ $field->getId() }}"
            class="form-select {{ $field->hasErrors() ? 'is-invalid' : '' }} {{ $field->getClass() }}"
            @if($field->isRequired()) required @endif
            @if($field->isDisabled()) disabled @endif
            @if($field->getAttribute('multiple')) multiple @endif
            @if($field->getAttribute('size')) size="{{ $field->getAttribute('size') }}" @endif
            {!! $field->getAttributesString() !!}>
        
        @if(!$field->getAttribute('multiple') && $field->getAttribute('placeholder'))
            <option value="">{{ $field->getAttribute('placeholder') }}</option>
        @endif

        @php
            $selectedValue = old($field->getName(), $field->getValue());
            $selectedValues = is_array($selectedValue) ? $selectedValue : [$selectedValue];
        @endphp

        @foreach($field->getOptions() as $value => $label)
            @if(is_array($label))
                {{-- Option group --}}
                <optgroup label="{{ $value }}">
                    @foreach($label as $optValue => $optLabel)
                        <option value="{{ $optValue }}" 
                                @if(in_array($optValue, $selectedValues)) selected @endif>
                            {{ $optLabel }}
                        </option>
                    @endforeach
                </optgroup>
            @else
                <option value="{{ $value }}" 
                        @if(in_array($value, $selectedValues)) selected @endif>
                    {{ $label }}
                </option>
            @endif
        @endforeach
    </select>

    @if($field->getAttribute('searchable', false))
        <script>
            // Initialize Select2 or similar search functionality
            document.addEventListener('DOMContentLoaded', function() {
                const select = document.getElementById('{{ $field->getId() }}');
                // Add search functionality here
            });
        </script>
    @endif

    @if($field->getHelp())
        <div class="form-text">{{ $field->getHelp() }}</div>
    @endif

    @if($field->hasErrors())
        <div class="invalid-feedback">
            {{ $field->getFirstError() }}
        </div>
    @endif
</div>
