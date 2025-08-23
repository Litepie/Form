{{-- Bootstrap 5 Rich Text Editor Field --}}
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
        $height = $field->getAttribute('height', '300px');
        $toolbar = $field->getAttribute('toolbar', 'basic');
        $uploadUrl = $field->getAttribute('uploadUrl', route('form.upload.image', ['field' => $field->getName()]));
    @endphp

    <div class="richtext-container">
        {{-- Toolbar --}}
        <div class="richtext-toolbar border rounded-top p-2 bg-light" id="{{ $field->getId() }}_toolbar">
            @if($toolbar === 'full' || $toolbar === 'basic')
                <div class="btn-group me-2" role="group">
                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                            onclick="execCommand('{{ $field->getId() }}', 'bold')"
                            title="Bold">
                        <i class="fas fa-bold"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                            onclick="execCommand('{{ $field->getId() }}', 'italic')"
                            title="Italic">
                        <i class="fas fa-italic"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                            onclick="execCommand('{{ $field->getId() }}', 'underline')"
                            title="Underline">
                        <i class="fas fa-underline"></i>
                    </button>
                </div>

                <div class="btn-group me-2" role="group">
                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                            onclick="execCommand('{{ $field->getId() }}', 'justifyLeft')"
                            title="Align Left">
                        <i class="fas fa-align-left"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                            onclick="execCommand('{{ $field->getId() }}', 'justifyCenter')"
                            title="Align Center">
                        <i class="fas fa-align-center"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                            onclick="execCommand('{{ $field->getId() }}', 'justifyRight')"
                            title="Align Right">
                        <i class="fas fa-align-right"></i>
                    </button>
                </div>

                <div class="btn-group me-2" role="group">
                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                            onclick="execCommand('{{ $field->getId() }}', 'insertUnorderedList')"
                            title="Bullet List">
                        <i class="fas fa-list-ul"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                            onclick="execCommand('{{ $field->getId() }}', 'insertOrderedList')"
                            title="Numbered List">
                        <i class="fas fa-list-ol"></i>
                    </button>
                </div>
            @endif

            @if($toolbar === 'full')
                <div class="btn-group me-2" role="group">
                    <select class="form-select form-select-sm" 
                            onchange="execCommand('{{ $field->getId() }}', 'formatBlock', this.value)"
                            style="width: auto;">
                        <option value="">Format</option>
                        <option value="<h1>">Heading 1</option>
                        <option value="<h2>">Heading 2</option>
                        <option value="<h3>">Heading 3</option>
                        <option value="<p>">Paragraph</option>
                        <option value="<blockquote>">Quote</option>
                    </select>
                </div>

                <div class="btn-group me-2" role="group">
                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                            onclick="insertLink('{{ $field->getId() }}')"
                            title="Insert Link">
                        <i class="fas fa-link"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                            onclick="insertImage('{{ $field->getId() }}')"
                            title="Insert Image">
                        <i class="fas fa-image"></i>
                    </button>
                </div>

                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                            onclick="toggleSource('{{ $field->getId() }}')"
                            title="Source Code">
                        <i class="fas fa-code"></i>
                    </button>
                </div>
            @endif
        </div>

        {{-- Editor Area --}}
        <div class="richtext-editor border border-top-0 rounded-bottom {{ $field->hasErrors() ? 'border-danger' : '' }}"
             id="{{ $field->getId() }}_editor"
             contenteditable="true"
             style="min-height: {{ $height }}; padding: 12px; outline: none;"
             oninput="updateHiddenField('{{ $field->getId() }}')"
             onpaste="handlePaste(event, '{{ $field->getId() }}')"
             {!! $field->getAttributesString() !!}>
            {!! $value !!}
        </div>

        {{-- Hidden Input --}}
        <input type="hidden" 
               name="{{ $field->getName() }}" 
               id="{{ $field->getId() }}"
               value="{{ htmlspecialchars($value) }}"
               @if($field->isRequired()) required @endif>

        @if($field->hasErrors())
            <div class="text-danger mt-1">
                {{ $field->getFirstError() }}
            </div>
        @endif

        {{-- Character Counter --}}
        @if($field->getAttribute('maxlength'))
            <div class="text-end mt-1">
                <small class="text-muted">
                    <span id="{{ $field->getId() }}_count">0</span> / {{ $field->getAttribute('maxlength') }}
                </small>
            </div>
        @endif
    </div>

    @if($field->getHelp())
        <div class="form-text">{{ $field->getHelp() }}</div>
    @endif
</div>

<script>
function execCommand(fieldId, command, value = null) {
    const editor = document.getElementById(fieldId + '_editor');
    editor.focus();
    document.execCommand(command, false, value);
    updateHiddenField(fieldId);
}

function updateHiddenField(fieldId) {
    const editor = document.getElementById(fieldId + '_editor');
    const hiddenInput = document.getElementById(fieldId);
    const counter = document.getElementById(fieldId + '_count');
    
    hiddenInput.value = editor.innerHTML;
    
    if (counter) {
        const textContent = editor.textContent || editor.innerText || '';
        counter.textContent = textContent.length;
    }
}

function insertLink(fieldId) {
    const url = prompt('Enter URL:');
    if (url) {
        execCommand(fieldId, 'createLink', url);
    }
}

function insertImage(fieldId) {
    const url = prompt('Enter image URL:');
    if (url) {
        execCommand(fieldId, 'insertImage', url);
    }
}

function toggleSource(fieldId) {
    const editor = document.getElementById(fieldId + '_editor');
    const toolbar = document.getElementById(fieldId + '_toolbar');
    
    if (editor.contentEditable === 'true') {
        // Switch to source mode
        const html = editor.innerHTML;
        editor.innerHTML = '';
        const textarea = document.createElement('textarea');
        textarea.value = html;
        textarea.style.width = '100%';
        textarea.style.height = editor.style.minHeight;
        textarea.style.border = 'none';
        textarea.style.outline = 'none';
        textarea.style.resize = 'vertical';
        textarea.className = 'form-control';
        editor.appendChild(textarea);
        editor.contentEditable = 'false';
        toolbar.style.display = 'none';
        
        textarea.addEventListener('input', function() {
            document.getElementById(fieldId).value = this.value;
        });
    } else {
        // Switch back to visual mode
        const textarea = editor.querySelector('textarea');
        const html = textarea ? textarea.value : '';
        editor.innerHTML = html;
        editor.contentEditable = 'true';
        toolbar.style.display = 'block';
        updateHiddenField(fieldId);
    }
}

function handlePaste(event, fieldId) {
    // Clean pasted content
    setTimeout(() => {
        updateHiddenField(fieldId);
    }, 10);
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    const editors = document.querySelectorAll('[id$="_editor"]');
    editors.forEach(editor => {
        const fieldId = editor.id.replace('_editor', '');
        updateHiddenField(fieldId);
    });
});
</script>
