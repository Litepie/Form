{{-- Bootstrap 5 Textarea Field --}}
<div class="mb-3 {{ $field->getWrapperClass() }}" {!! $field->getWrapperAttributes() !!}>
    @if($field->getLabel())
        <label for="{{ $field->getId() }}" class="form-label">
            {{ $field->getLabel() }}
            @if($field->isRequired())
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <textarea name="{{ $field->getName() }}" 
              id="{{ $field->getId() }}"
              class="form-control {{ $field->hasErrors() ? 'is-invalid' : '' }} {{ $field->getClass() }}"
              rows="{{ $field->getAttribute('rows', 3) }}"
              @if($field->getPlaceholder()) placeholder="{{ $field->getPlaceholder() }}" @endif
              @if($field->isRequired()) required @endif
              @if($field->isDisabled()) disabled @endif
              @if($field->isReadonly()) readonly @endif
              @if($field->getAttribute('maxlength')) maxlength="{{ $field->getAttribute('maxlength') }}" @endif
              {!! $field->getAttributesString() !!}>{{ old($field->getName(), $field->getValue()) }}</textarea>

    @if($field->getAttribute('showCounter', false) && $field->getAttribute('maxlength'))
        <div class="form-text">
            <span id="{{ $field->getId() }}_counter">0</span> / {{ $field->getAttribute('maxlength') }} characters
        </div>
        <script>
            document.getElementById('{{ $field->getId() }}').addEventListener('input', function() {
                document.getElementById('{{ $field->getId() }}_counter').textContent = this.value.length;
            });
            // Initialize counter
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('{{ $field->getId() }}_counter').textContent = 
                    document.getElementById('{{ $field->getId() }}').value.length;
            });
        </script>
    @endif

    @if($field->getAttribute('autoResize', true))
        <script>
            document.getElementById('{{ $field->getId() }}').addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
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
