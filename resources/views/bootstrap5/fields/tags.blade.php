{{-- Bootstrap 5 Tags/Multiple Select Field --}}
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
        $values = old($field->getName(), $field->getValue()) ?: [];
        if (!is_array($values)) {
            $values = explode(',', $values);
        }
        $values = array_filter(array_map('trim', $values));
        
        $options = $field->getOptions();
        $allowCustom = $field->getAttribute('allowCustom', true);
        $maxTags = $field->getAttribute('maxTags');
        $placeholder = $field->getAttribute('placeholder', 'Type and press Enter to add tags...');
    @endphp

    <div class="tags-container" data-field-id="{{ $field->getId() }}">
        {{-- Tags Display --}}
        <div class="tags-input border rounded p-2 {{ $field->hasErrors() ? 'border-danger' : '' }}"
             onclick="focusTagInput('{{ $field->getId() }}')"
             style="min-height: 42px; cursor: text;">
            
            <div class="tags-list d-inline-flex flex-wrap gap-1" id="{{ $field->getId() }}_tags">
                @foreach($values as $value)
                    <span class="badge bg-primary tag-item" data-value="{{ $value }}">
                        {{ isset($options[$value]) ? $options[$value] : $value }}
                        <button type="button" class="btn-close btn-close-white ms-1" 
                                onclick="removeTag('{{ $field->getId() }}', '{{ $value }}')"
                                style="font-size: 0.65em;"
                                aria-label="Remove tag"></button>
                    </span>
                @endforeach
            </div>
            
            <input type="text" 
                   id="{{ $field->getId() }}_input"
                   class="tag-input border-0 outline-0"
                   placeholder="{{ empty($values) ? $placeholder : '' }}"
                   style="outline: none; box-shadow: none; flex: 1; min-width: 120px;"
                   onkeydown="handleTagInput(event, '{{ $field->getId() }}')"
                   oninput="filterTagSuggestions('{{ $field->getId() }}')"
                   @if($field->isDisabled()) disabled @endif
                   autocomplete="off">
        </div>

        {{-- Suggestions Dropdown --}}
        @if($options)
            <div class="suggestions-dropdown position-relative">
                <div class="dropdown-menu w-100" 
                     id="{{ $field->getId() }}_suggestions"
                     style="display: none; max-height: 200px; overflow-y: auto;">
                    @foreach($options as $value => $label)
                        <button type="button" 
                                class="dropdown-item suggestion-item" 
                                data-value="{{ $value }}"
                                onclick="addTag('{{ $field->getId() }}', '{{ $value }}', '{{ $label }}')">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Hidden Inputs --}}
        <div id="{{ $field->getId() }}_hidden">
            @foreach($values as $value)
                <input type="hidden" name="{{ $field->getName() }}[]" value="{{ $value }}">
            @endforeach
        </div>

        @if($field->hasErrors())
            <div class="text-danger mt-1">
                {{ $field->getFirstError() }}
            </div>
        @endif

        @if($maxTags)
            <div class="text-end mt-1">
                <small class="text-muted">
                    <span id="{{ $field->getId() }}_count">{{ count($values) }}</span> / {{ $maxTags }} tags
                </small>
            </div>
        @endif
    </div>

    @if($field->getHelp())
        <div class="form-text">{{ $field->getHelp() }}</div>
    @endif
</div>

<script>
function addTag(fieldId, value, label = null) {
    const container = document.querySelector(`[data-field-id="${fieldId}"]`);
    const tagsContainer = document.getElementById(fieldId + '_tags');
    const hiddenContainer = document.getElementById(fieldId + '_hidden');
    const input = document.getElementById(fieldId + '_input');
    const maxTags = {{ $maxTags ?: 'null' }};
    const allowCustom = {{ $allowCustom ? 'true' : 'false' }};
    
    // Check if tag already exists
    if (tagsContainer.querySelector(`[data-value="${value}"]`)) {
        input.value = '';
        hideSuggestions(fieldId);
        return;
    }
    
    // Check max tags limit
    const currentCount = tagsContainer.children.length;
    if (maxTags && currentCount >= maxTags) {
        alert(`Maximum ${maxTags} tags allowed`);
        return;
    }
    
    // Create tag element
    const tag = document.createElement('span');
    tag.className = 'badge bg-primary tag-item';
    tag.setAttribute('data-value', value);
    tag.innerHTML = `
        ${label || value}
        <button type="button" class="btn-close btn-close-white ms-1" 
                onclick="removeTag('${fieldId}', '${value}')"
                style="font-size: 0.65em;"
                aria-label="Remove tag"></button>
    `;
    
    // Create hidden input
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = '{{ $field->getName() }}[]';
    hiddenInput.value = value;
    
    // Add to DOM
    tagsContainer.appendChild(tag);
    hiddenContainer.appendChild(hiddenInput);
    
    // Clear input and update placeholder
    input.value = '';
    updatePlaceholder(fieldId);
    updateTagCount(fieldId);
    hideSuggestions(fieldId);
}

function removeTag(fieldId, value) {
    const tagsContainer = document.getElementById(fieldId + '_tags');
    const hiddenContainer = document.getElementById(fieldId + '_hidden');
    
    // Remove tag element
    const tagElement = tagsContainer.querySelector(`[data-value="${value}"]`);
    if (tagElement) {
        tagElement.remove();
    }
    
    // Remove hidden input
    const hiddenInput = hiddenContainer.querySelector(`input[value="${value}"]`);
    if (hiddenInput) {
        hiddenInput.remove();
    }
    
    updatePlaceholder(fieldId);
    updateTagCount(fieldId);
}

function handleTagInput(event, fieldId) {
    const input = event.target;
    const value = input.value.trim();
    
    if (event.key === 'Enter' || event.key === ',') {
        event.preventDefault();
        if (value) {
            addTag(fieldId, value);
        }
    } else if (event.key === 'Backspace' && !value) {
        // Remove last tag on backspace
        const tagsContainer = document.getElementById(fieldId + '_tags');
        const lastTag = tagsContainer.lastElementChild;
        if (lastTag) {
            const tagValue = lastTag.getAttribute('data-value');
            removeTag(fieldId, tagValue);
        }
    } else if (event.key === 'Escape') {
        hideSuggestions(fieldId);
    } else if (event.key === 'ArrowDown') {
        navigateSuggestions(fieldId, 'down');
        event.preventDefault();
    } else if (event.key === 'ArrowUp') {
        navigateSuggestions(fieldId, 'up');
        event.preventDefault();
    }
}

function filterTagSuggestions(fieldId) {
    const input = document.getElementById(fieldId + '_input');
    const suggestions = document.getElementById(fieldId + '_suggestions');
    
    if (!suggestions) return;
    
    const query = input.value.toLowerCase();
    const items = suggestions.querySelectorAll('.suggestion-item');
    let hasVisible = false;
    
    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        const value = item.getAttribute('data-value');
        const isSelected = document.getElementById(fieldId + '_tags').querySelector(`[data-value="${value}"]`);
        
        if (!isSelected && (text.includes(query) || value.includes(query))) {
            item.style.display = 'block';
            hasVisible = true;
        } else {
            item.style.display = 'none';
        }
    });
    
    suggestions.style.display = hasVisible && query ? 'block' : 'none';
}

function hideSuggestions(fieldId) {
    const suggestions = document.getElementById(fieldId + '_suggestions');
    if (suggestions) {
        suggestions.style.display = 'none';
    }
}

function focusTagInput(fieldId) {
    const input = document.getElementById(fieldId + '_input');
    input.focus();
}

function updatePlaceholder(fieldId) {
    const input = document.getElementById(fieldId + '_input');
    const tagsContainer = document.getElementById(fieldId + '_tags');
    const hasChildren = tagsContainer.children.length > 0;
    
    input.placeholder = hasChildren ? '' : '{{ $placeholder }}';
}

function updateTagCount(fieldId) {
    const counter = document.getElementById(fieldId + '_count');
    if (counter) {
        const tagsContainer = document.getElementById(fieldId + '_tags');
        counter.textContent = tagsContainer.children.length;
    }
}

function navigateSuggestions(fieldId, direction) {
    const suggestions = document.getElementById(fieldId + '_suggestions');
    if (!suggestions || suggestions.style.display === 'none') return;
    
    const items = Array.from(suggestions.querySelectorAll('.suggestion-item[style*="block"]'));
    const active = suggestions.querySelector('.suggestion-item.active');
    let newIndex = 0;
    
    if (active) {
        const currentIndex = items.indexOf(active);
        newIndex = direction === 'down' 
            ? Math.min(currentIndex + 1, items.length - 1)
            : Math.max(currentIndex - 1, 0);
        active.classList.remove('active');
    }
    
    if (items[newIndex]) {
        items[newIndex].classList.add('active');
    }
}

// Click outside to hide suggestions
document.addEventListener('click', function(event) {
    const tagContainers = document.querySelectorAll('.tags-container');
    tagContainers.forEach(container => {
        if (!container.contains(event.target)) {
            const fieldId = container.getAttribute('data-field-id');
            hideSuggestions(fieldId);
        }
    });
});
</script>

<style>
.tag-input {
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
}

.tags-input:focus-within {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.suggestion-item.active {
    background-color: var(--bs-primary);
    color: white;
}

.tag-item .btn-close {
    --bs-btn-close-bg: none;
}
</style>
