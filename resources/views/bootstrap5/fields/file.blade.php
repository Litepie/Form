{{-- Bootstrap 5 File Upload Field --}}
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
        $maxSize = $field->getAttribute('maxSize', '10MB');
        $accept = $field->getAttribute('accept', '');
        $multiple = $field->getAttribute('multiple', false);
        $dragDrop = $field->getAttribute('dragDrop', true);
    @endphp

    @if($dragDrop)
        <div class="file-upload-area border border-2 border-dashed rounded p-4 text-center position-relative"
             id="{{ $field->getId() }}_drop_area"
             ondrop="handleDrop(event, '{{ $field->getId() }}')" 
             ondragover="handleDragOver(event)"
             ondragenter="handleDragEnter(event, '{{ $field->getId() }}')"
             ondragleave="handleDragLeave(event, '{{ $field->getId() }}')">
            
            <div class="file-upload-content">
                <i class="bi bi-cloud-upload fs-1 text-muted"></i>
                <p class="mb-2">
                    <strong>Drag & drop {{ $multiple ? 'files' : 'a file' }} here</strong>
                </p>
                <p class="text-muted">or</p>
                <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('{{ $field->getId() }}').click()">
                    <i class="bi bi-folder-open"></i> Browse {{ $multiple ? 'Files' : 'File' }}
                </button>
            </div>

            <input type="file" 
                   name="{{ $field->getName() }}{{ $multiple ? '[]' : '' }}" 
                   id="{{ $field->getId() }}"
                   class="form-control d-none {{ $field->hasErrors() ? 'is-invalid' : '' }}"
                   @if($accept) accept="{{ $accept }}" @endif
                   @if($multiple) multiple @endif
                   @if($field->isRequired()) required @endif
                   @if($field->isDisabled()) disabled @endif
                   onchange="handleFileSelect(event, '{{ $field->getId() }}')"
                   {!! $field->getAttributesString() !!}>
        </div>

        <div id="{{ $field->getId() }}_file_list" class="file-list mt-3"></div>
    @else
        <input type="file" 
               name="{{ $field->getName() }}{{ $multiple ? '[]' : '' }}" 
               id="{{ $field->getId() }}"
               class="form-control {{ $field->hasErrors() ? 'is-invalid' : '' }}"
               @if($accept) accept="{{ $accept }}" @endif
               @if($multiple) multiple @endif
               @if($field->isRequired()) required @endif
               @if($field->isDisabled()) disabled @endif
               {!! $field->getAttributesString() !!}>
    @endif

    @if($accept || $maxSize)
        <div class="form-text">
            @if($accept)
                Accepted file types: {{ $accept }}.
            @endif
            @if($maxSize)
                Maximum file size: {{ $maxSize }}.
            @endif
        </div>
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

@if($dragDrop)
<script>
function handleDragOver(e) {
    e.preventDefault();
}

function handleDragEnter(e, fieldId) {
    e.preventDefault();
    document.getElementById(fieldId + '_drop_area').classList.add('border-primary', 'bg-light');
}

function handleDragLeave(e, fieldId) {
    if (!e.currentTarget.contains(e.relatedTarget)) {
        document.getElementById(fieldId + '_drop_area').classList.remove('border-primary', 'bg-light');
    }
}

function handleDrop(e, fieldId) {
    e.preventDefault();
    const dropArea = document.getElementById(fieldId + '_drop_area');
    dropArea.classList.remove('border-primary', 'bg-light');
    
    const files = e.dataTransfer.files;
    const input = document.getElementById(fieldId);
    input.files = files;
    
    handleFileSelect({ target: input }, fieldId);
}

function handleFileSelect(e, fieldId) {
    const files = e.target.files;
    const fileList = document.getElementById(fieldId + '_file_list');
    fileList.innerHTML = '';
    
    Array.from(files).forEach((file, index) => {
        const fileItem = document.createElement('div');
        fileItem.className = 'file-item d-flex align-items-center justify-content-between border rounded p-2 mb-2';
        fileItem.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="bi bi-file-earmark me-2"></i>
                <span>${file.name}</span>
                <small class="text-muted ms-2">(${formatFileSize(file.size)})</small>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile(${index}, '${fieldId}')">
                <i class="bi bi-trash"></i>
            </button>
        `;
        fileList.appendChild(fileItem);
    });
}

function removeFile(index, fieldId) {
    const input = document.getElementById(fieldId);
    const dt = new DataTransfer();
    const files = Array.from(input.files);
    
    files.forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    input.files = dt.files;
    handleFileSelect({ target: input }, fieldId);
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}
</script>
@endif
