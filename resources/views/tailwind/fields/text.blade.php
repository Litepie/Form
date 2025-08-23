{{-- Tailwind CSS Text Field --}}
<div class="space-y-1 {{ $field->getWrapperClass() }}" {!! $field->getWrapperAttributes() !!}>
    @if($field->getLabel())
        <label for="{{ $field->getId() }}" class="block text-sm font-medium text-gray-700">
            {{ $field->getLabel() }}
            @if($field->isRequired())
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    @php
        $value = old($field->getName(), $field->getValue());
        $hasIcon = $field->getAttribute('icon');
        $hasPrefix = $field->getAttribute('prefix');
        $hasSuffix = $field->getAttribute('suffix');
        $maxlength = $field->getAttribute('maxlength');
    @endphp

    <div class="relative {{ $hasIcon || $hasPrefix || $hasSuffix ? 'flex' : '' }}">
        @if($hasPrefix)
            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                {{ $hasPrefix }}
            </span>
        @endif

        @if($hasIcon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="{{ $hasIcon }} text-gray-400"></i>
            </div>
        @endif

        <input type="text" 
               name="{{ $field->getName() }}" 
               id="{{ $field->getId() }}"
               value="{{ $value }}"
               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm
                      {{ $field->hasErrors() ? 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500' : '' }}
                      {{ $hasIcon ? 'pl-10' : '' }}
                      {{ $hasPrefix ? 'rounded-l-none' : '' }}
                      {{ $hasSuffix ? 'rounded-r-none' : '' }}
                      {{ $field->getClass() }}"
               placeholder="{{ $field->getAttribute('placeholder') }}"
               @if($field->isRequired()) required @endif
               @if($field->isDisabled()) disabled @endif
               @if($maxlength) maxlength="{{ $maxlength }}" @endif
               @if($maxlength) oninput="updateCharCount('{{ $field->getId() }}')" @endif
               {!! $field->getAttributesString() !!}>

        @if($hasSuffix)
            <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                {{ $hasSuffix }}
            </span>
        @endif

        @if($field->hasErrors())
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
        @endif
    </div>

    @if($field->hasErrors())
        <p class="text-sm text-red-600">{{ $field->getFirstError() }}</p>
    @endif

    @if($field->getHelp())
        <p class="text-sm text-gray-500">{{ $field->getHelp() }}</p>
    @endif

    @if($maxlength)
        <div class="flex justify-end">
            <span class="text-xs text-gray-400">
                <span id="{{ $field->getId() }}_count">{{ strlen($value) }}</span> / {{ $maxlength }}
            </span>
        </div>
    @endif
</div>

@if($maxlength)
<script>
function updateCharCount(fieldId) {
    const input = document.getElementById(fieldId);
    const counter = document.getElementById(fieldId + '_count');
    if (counter) {
        counter.textContent = input.value.length;
    }
}
</script>
@endif
