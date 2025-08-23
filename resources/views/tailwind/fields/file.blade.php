{{-- Tailwind CSS File Upload Field --}}
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
        $multiple = $field->getAttribute('multiple', false);
        $accept = $field->getAttribute('accept');
        $maxSize = $field->getAttribute('maxSize', '10MB');
        $maxFiles = $field->getAttribute('maxFiles', 5);
        $showPreview = $field->getAttribute('showPreview', true);
        $dragDrop = $field->getAttribute('dragDrop', true);
    @endphp

    <div class="file-upload-container" data-field-id="{{ $field->getId() }}">
        @if($dragDrop)
            {{-- Drag & Drop Area --}}
            <div class="relative border-2 border-gray-300 border-dashed rounded-lg p-6 hover:border-gray-400 focus:border-indigo-500 transition-colors duration-200
                        {{ $field->hasErrors() ? 'border-red-300' : '' }}"
                 id="{{ $field->getId() }}_dropzone"
                 ondrop="handleDrop(event, '{{ $field->getId() }}')"
                 ondragover="handleDragOver(event)"
                 ondragenter="handleDragEnter(event, '{{ $field->getId() }}')"
                 ondragleave="handleDragLeave(event, '{{ $field->getId() }}')">
                
                <input type="file" 
                       name="{{ $field->getName() }}{{ $multiple ? '[]' : '' }}" 
                       id="{{ $field->getId() }}"
                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                       @if($multiple) multiple @endif
                       @if($accept) accept="{{ $accept }}" @endif
                       @if($field->isRequired()) required @endif
                       @if($field->isDisabled()) disabled @endif
                       onchange="handleFileSelect(event, '{{ $field->getId() }}')"
                       {!! $field->getAttributesString() !!}>
                
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="mt-4">
                        <p class="text-sm text-gray-600">
                            <span class="font-medium text-indigo-600 hover:text-indigo-500 cursor-pointer">
                                Click to upload
                            </span>
                            or drag and drop
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($accept)
                                {{ $accept }} files,
                            @endif
                            up to {{ $maxSize }}
                            @if($multiple)
                                (max {{ $maxFiles }} files)
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @else
            {{-- Standard File Input --}}
            <div class="relative">
                <input type="file" 
                       name="{{ $field->getName() }}{{ $multiple ? '[]' : '' }}" 
                       id="{{ $field->getId() }}"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                       @if($multiple) multiple @endif
                       @if($accept) accept="{{ $accept }}" @endif
                       @if($field->isRequired()) required @endif
                       @if($field->isDisabled()) disabled @endif
                       onchange="handleFileSelect(event, '{{ $field->getId() }}')"
                       {!! $field->getAttributesString() !!}>
            </div>
        @endif

        {{-- File Preview Area --}}
        @if($showPreview)
            <div class="mt-4 space-y-3" id="{{ $field->getId() }}_preview" style="display: none;">
                <h4 class="text-sm font-medium text-gray-700">Selected Files:</h4>
                <div class="space-y-2" id="{{ $field->getId() }}_file_list">
                    {{-- Files will be listed here --}}
                </div>
            </div>
        @endif

        {{-- Upload Progress --}}
        <div class="mt-4 hidden" id="{{ $field->getId() }}_progress">
            <div class="bg-gray-200 rounded-full h-2">
                <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" 
                     id="{{ $field->getId() }}_progress_bar" 
                     style="width: 0%"></div>
            </div>
            <p class="text-sm text-gray-600 mt-1" id="{{ $field->getId() }}_progress_text">Uploading...</p>
        </div>
    </div>

    @if($field->hasErrors())
        <p class="text-sm text-red-600">{{ $field->getFirstError() }}</p>
    @endif

    @if($field->getHelp())
        <p class="text-sm text-gray-500">{{ $field->getHelp() }}</p>
    @endif
</div>

<script>
function handleDragOver(event) {
    event.preventDefault();
}

function handleDragEnter(event, fieldId) {
    event.preventDefault();
    const dropzone = document.getElementById(fieldId + '_dropzone');
    dropzone.classList.add('border-indigo-500', 'bg-indigo-50');
}

function handleDragLeave(event, fieldId) {
    event.preventDefault();
    const dropzone = document.getElementById(fieldId + '_dropzone');
    if (!dropzone.contains(event.relatedTarget)) {
        dropzone.classList.remove('border-indigo-500', 'bg-indigo-50');
    }
}

function handleDrop(event, fieldId) {
    event.preventDefault();
    const dropzone = document.getElementById(fieldId + '_dropzone');
    dropzone.classList.remove('border-indigo-500', 'bg-indigo-50');
    
    const files = event.dataTransfer.files;
    const input = document.getElementById(fieldId);
    
    // Create a new FileList-like object
    const dt = new DataTransfer();
    for (let file of files) {
        dt.items.add(file);
    }
    
    input.files = dt.files;
    handleFileSelect({ target: input }, fieldId);
}

function handleFileSelect(event, fieldId) {
    const files = event.target.files;
    const preview = document.getElementById(fieldId + '_preview');
    const fileList = document.getElementById(fieldId + '_file_list');
    const maxFiles = {{ $maxFiles }};
    const maxSizeBytes = parseSize('{{ $maxSize }}');
    
    if (!files.length) {
        preview.style.display = 'none';
        return;
    }
    
    // Validate file count
    if (files.length > maxFiles) {
        alert(`Maximum ${maxFiles} files allowed`);
        event.target.value = '';
        return;
    }
    
    // Clear previous preview
    fileList.innerHTML = '';
    
    let totalSize = 0;
    for (let file of files) {
        totalSize += file.size;
        
        // Validate individual file size
        if (file.size > maxSizeBytes) {
            alert(`File "${file.name}" is too large. Maximum size is {{ $maxSize }}`);
            event.target.value = '';
            return;
        }
        
        // Create file preview
        const fileItem = createFilePreview(file, fieldId);
        fileList.appendChild(fileItem);
    }
    
    preview.style.display = 'block';
}

function createFilePreview(file, fieldId) {
    const fileItem = document.createElement('div');
    fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg border';
    
    const isImage = file.type.startsWith('image/');
    const fileSize = formatFileSize(file.size);
    
    fileItem.innerHTML = `
        <div class="flex items-center space-x-3">
            ${isImage ? 
                `<div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-lg object-cover" src="${URL.createObjectURL(file)}" alt="${file.name}">
                 </div>` :
                `<div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                    </svg>
                 </div>`
            }
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">${file.name}</p>
                <p class="text-sm text-gray-500">${fileSize}</p>
            </div>
        </div>
        <button type="button" 
                onclick="removeFile('${fieldId}', '${file.name}')"
                class="ml-4 text-red-400 hover:text-red-600">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    `;
    
    return fileItem;
}

function removeFile(fieldId, fileName) {
    const input = document.getElementById(fieldId);
    const dt = new DataTransfer();
    
    for (let file of input.files) {
        if (file.name !== fileName) {
            dt.items.add(file);
        }
    }
    
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

function parseSize(sizeStr) {
    const units = { B: 1, KB: 1024, MB: 1024 * 1024, GB: 1024 * 1024 * 1024 };
    const match = sizeStr.match(/^(\d+(?:\.\d+)?)\s*(B|KB|MB|GB)$/i);
    if (!match) return 0;
    return parseFloat(match[1]) * (units[match[2].toUpperCase()] || 1);
}

// Simulate upload progress (replace with actual upload logic)
function simulateUpload(fieldId) {
    const progressContainer = document.getElementById(fieldId + '_progress');
    const progressBar = document.getElementById(fieldId + '_progress_bar');
    const progressText = document.getElementById(fieldId + '_progress_text');
    
    progressContainer.classList.remove('hidden');
    
    let progress = 0;
    const interval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress >= 100) {
            progress = 100;
            clearInterval(interval);
            progressText.textContent = 'Upload complete!';
            setTimeout(() => {
                progressContainer.classList.add('hidden');
                progressBar.style.width = '0%';
            }, 2000);
        }
        
        progressBar.style.width = progress + '%';
        progressText.textContent = `Uploading... ${Math.round(progress)}%`;
    }, 200);
}
</script>
