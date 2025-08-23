{{-- Bootstrap 5 Color Picker Field --}}
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
        $value = old($field->getName(), $field->getValue()) ?: '#000000';
        $palette = $field->getAttribute('palette', [
            '#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff', '#00ffff',
            '#000000', '#ffffff', '#808080', '#800000', '#008000', '#000080'
        ]);
        $allowAlpha = $field->getAttribute('allowAlpha', false);
    @endphp

    <div class="color-picker-container">
        <div class="input-group">
            <div class="color-preview border rounded-start d-flex align-items-center justify-content-center" 
                 style="width: 50px; background-color: {{ $value }};" 
                 id="{{ $field->getId() }}_preview">
            </div>
            
            <input type="color" 
                   name="{{ $field->getName() }}" 
                   id="{{ $field->getId() }}"
                   value="{{ $value }}"
                   class="form-control form-control-color {{ $field->hasErrors() ? 'is-invalid' : '' }} {{ $field->getClass() }}"
                   @if($field->isRequired()) required @endif
                   @if($field->isDisabled()) disabled @endif
                   onchange="updateColorPreview('{{ $field->getId() }}')"
                   {!! $field->getAttributesString() !!}>
            
            <input type="text" 
                   id="{{ $field->getId() }}_text"
                   value="{{ $value }}"
                   class="form-control"
                   placeholder="#000000"
                   onchange="updateColorFromText('{{ $field->getId() }}')"
                   style="max-width: 100px;">
            
            @if($field->hasErrors())
                <div class="invalid-feedback">
                    {{ $field->getFirstError() }}
                </div>
            @endif
        </div>

        @if($palette)
            <div class="color-palette mt-2">
                <small class="form-text text-muted">Quick colors:</small>
                <div class="d-flex flex-wrap gap-1 mt-1">
                    @foreach($palette as $color)
                        <div class="color-swatch border rounded" 
                             style="width: 24px; height: 24px; background-color: {{ $color }}; cursor: pointer;"
                             onclick="setColor('{{ $field->getId() }}', '{{ $color }}')"
                             title="{{ $color }}">
                        </div>
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
function updateColorPreview(fieldId) {
    const colorInput = document.getElementById(fieldId);
    const preview = document.getElementById(fieldId + '_preview');
    const textInput = document.getElementById(fieldId + '_text');
    
    preview.style.backgroundColor = colorInput.value;
    textInput.value = colorInput.value;
}

function updateColorFromText(fieldId) {
    const textInput = document.getElementById(fieldId + '_text');
    const colorInput = document.getElementById(fieldId);
    const preview = document.getElementById(fieldId + '_preview');
    
    if (/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(textInput.value)) {
        colorInput.value = textInput.value;
        preview.style.backgroundColor = textInput.value;
    }
}

function setColor(fieldId, color) {
    const colorInput = document.getElementById(fieldId);
    const textInput = document.getElementById(fieldId + '_text');
    const preview = document.getElementById(fieldId + '_preview');
    
    colorInput.value = color;
    textInput.value = color;
    preview.style.backgroundColor = color;
}
</script>
