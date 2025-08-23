{{-- Bootstrap 5 Range/Slider Field --}}
<div class="mb-3 {{ $field->getWrapperClass() }}" {!! $field->getWrapperAttributes() !!}>
    @if($field->getLabel())
        <label for="{{ $field->getId() }}" class="form-label">
            {{ $field->getLabel() }}
            @if($field->isRequired())
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    @php
        $min = $field->getAttribute('min', 0);
        $max = $field->getAttribute('max', 100);
        $step = $field->getAttribute('step', 1);
        $value = old($field->getName(), $field->getValue()) ?: $min;
        $showValue = $field->getAttribute('showValue', true);
        $prefix = $field->getAttribute('valuePrefix', '');
        $suffix = $field->getAttribute('valueSuffix', '');
    @endphp

    <div class="range-slider-container">
        @if($showValue)
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="range-min">{{ $prefix }}{{ $min }}{{ $suffix }}</span>
                <span class="range-value fw-bold" id="{{ $field->getId() }}_display">
                    {{ $prefix }}{{ $value }}{{ $suffix }}
                </span>
                <span class="range-max">{{ $prefix }}{{ $max }}{{ $suffix }}</span>
            </div>
        @endif

        <input type="range" 
               name="{{ $field->getName() }}" 
               id="{{ $field->getId() }}"
               value="{{ $value }}"
               min="{{ $min }}"
               max="{{ $max }}"
               step="{{ $step }}"
               class="form-range {{ $field->hasErrors() ? 'is-invalid' : '' }} {{ $field->getClass() }}"
               @if($field->isRequired()) required @endif
               @if($field->isDisabled()) disabled @endif
               oninput="updateRangeValue('{{ $field->getId() }}', '{{ $prefix }}', '{{ $suffix }}')"
               {!! $field->getAttributesString() !!}>

        @if($field->getAttribute('ticks'))
            <div class="range-ticks d-flex justify-content-between mt-1">
                @foreach($field->getAttribute('ticks') as $tick)
                    <small class="text-muted">{{ $tick }}</small>
                @endforeach
            </div>
        @endif
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

<script>
function updateRangeValue(fieldId, prefix, suffix) {
    const slider = document.getElementById(fieldId);
    const display = document.getElementById(fieldId + '_display');
    if (display) {
        display.textContent = prefix + slider.value + suffix;
    }
}
</script>
