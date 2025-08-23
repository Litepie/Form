{{-- Bootstrap 5 Time Field --}}
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
        $value = old($field->getName(), $field->getValue());
        $min = $field->getAttribute('min');
        $max = $field->getAttribute('max');
        $step = $field->getAttribute('step', 60); // Default to 1 minute steps
    @endphp

    <div class="time-input-container">
        <div class="input-group">
            <input type="time" 
                   name="{{ $field->getName() }}" 
                   id="{{ $field->getId() }}"
                   value="{{ $value }}"
                   class="form-control {{ $field->hasErrors() ? 'is-invalid' : '' }} {{ $field->getClass() }}"
                   @if($field->isRequired()) required @endif
                   @if($field->isDisabled()) disabled @endif
                   @if($min) min="{{ $min }}" @endif
                   @if($max) max="{{ $max }}" @endif
                   @if($step) step="{{ $step }}" @endif
                   onchange="formatTimeDisplay('{{ $field->getId() }}')"
                   {!! $field->getAttributesString() !!}>
            
            <button class="btn btn-outline-secondary" 
                    type="button" 
                    onclick="setCurrentTime('{{ $field->getId() }}')"
                    title="Set current time">
                <i class="fas fa-clock"></i>
            </button>
            
            @if($field->hasErrors())
                <div class="invalid-feedback">
                    {{ $field->getFirstError() }}
                </div>
            @endif
        </div>

        <div class="time-display mt-1" id="{{ $field->getId() }}_display" style="font-size: 0.875rem; color: #666;">
            {{-- Will show formatted time --}}
        </div>

        @if($field->getAttribute('presets'))
            <div class="time-presets mt-2">
                <small class="form-text text-muted">Quick times:</small>
                <div class="btn-group-sm mt-1" role="group">
                    @foreach($field->getAttribute('presets') as $label => $time)
                        <button type="button" 
                                class="btn btn-outline-secondary btn-sm"
                                onclick="setPresetTime('{{ $field->getId() }}', '{{ $time }}')">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @if($field->getHelp())
        <div class="form-text">{{ $field->getHelp() }}</div>
    @endif
</div>

<script>
function formatTimeDisplay(fieldId) {
    const input = document.getElementById(fieldId);
    const display = document.getElementById(fieldId + '_display');
    
    if (input.value) {
        try {
            const [hours, minutes] = input.value.split(':');
            const time = new Date();
            time.setHours(parseInt(hours), parseInt(minutes), 0, 0);
            
            const formatted = time.toLocaleTimeString([], {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
            
            display.textContent = formatted;
        } catch (e) {
            display.textContent = '';
        }
    } else {
        display.textContent = '';
    }
}

function setCurrentTime(fieldId) {
    const input = document.getElementById(fieldId);
    const now = new Date();
    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    
    input.value = `${hours}:${minutes}`;
    formatTimeDisplay(fieldId);
}

function setPresetTime(fieldId, time) {
    const input = document.getElementById(fieldId);
    input.value = time;
    formatTimeDisplay(fieldId);
}

// Initialize time display on page load
document.addEventListener('DOMContentLoaded', function() {
    const timeInputs = document.querySelectorAll('input[type="time"]');
    timeInputs.forEach(input => {
        if (input.value) {
            formatTimeDisplay(input.id);
        }
    });
});
</script>
