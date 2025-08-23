{{-- Tailwind CSS Form Template --}}
@php
    $formClass = 'space-y-6 ' . ($form->getClass() ?? '');
    $isMultiStep = $form->hasSteps();
    $currentStep = $form->getCurrentStep();
    $steps = $form->getSteps();
@endphp

<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-lg" {!! $form->getWrapperAttributes() !!}>
    @if($form->hasErrors())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        There {{ $form->getErrors()->count() === 1 ? 'is' : 'are' }} {{ $form->getErrors()->count() }} error{{ $form->getErrors()->count() === 1 ? '' : 's' }} with your submission
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($form->getErrors()->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($form->getTitle())
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ $form->getTitle() }}</h1>
            @if($form->getDescription())
                <p class="mt-2 text-lg text-gray-600">{{ $form->getDescription() }}</p>
            @endif
        </div>
    @endif

    @if($isMultiStep)
        {{-- Multi-step Progress Indicator --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                @foreach($steps as $index => $step)
                    @php
                        $stepNumber = $index + 1;
                        $isActive = $stepNumber === $currentStep;
                        $isCompleted = $stepNumber < $currentStep;
                        $isClickable = $stepNumber <= $currentStep;
                    @endphp
                    
                    <div class="flex-1 {{ $index > 0 ? 'ml-4' : '' }}">
                        <div class="flex items-center">
                            @if($index > 0)
                                <div class="flex-1 h-0.5 {{ $isCompleted ? 'bg-indigo-600' : 'bg-gray-200' }}"></div>
                            @endif
                            
                            <div class="relative flex items-center justify-center w-10 h-10 mx-2">
                                <button type="button" 
                                        class="w-10 h-10 rounded-full border-2 flex items-center justify-center text-sm font-medium transition-colors duration-200
                                               {{ $isActive ? 'border-indigo-600 bg-indigo-600 text-white' : '' }}
                                               {{ $isCompleted ? 'border-indigo-600 bg-indigo-600 text-white' : '' }}
                                               {{ !$isActive && !$isCompleted ? 'border-gray-300 bg-white text-gray-500' : '' }}
                                               {{ $isClickable ? 'hover:border-indigo-600 cursor-pointer' : 'cursor-not-allowed' }}"
                                        onclick="{{ $isClickable ? 'goToStep(' . $stepNumber . ')' : '' }}"
                                        title="{{ $step['title'] ?? 'Step ' . $stepNumber }}">
                                    @if($isCompleted)
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        {{ $stepNumber }}
                                    @endif
                                </button>
                            </div>
                        </div>
                        
                        @if($step['title'] ?? null)
                            <div class="mt-2 text-center">
                                <span class="text-sm font-medium {{ $isActive ? 'text-indigo-600' : 'text-gray-500' }}">
                                    {{ $step['title'] }}
                                </span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            
            {{-- Progress Bar --}}
            <div class="mt-6">
                <div class="bg-gray-200 rounded-full h-2">
                    <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" 
                         style="width: {{ (($currentStep - 1) / (count($steps) - 1)) * 100 }}%"></div>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-sm text-gray-600">
                        Step {{ $currentStep }} of {{ count($steps) }}
                    </span>
                </div>
            </div>
        </div>
    @endif

    <form {!! $form->getFormAttributes() !!} 
          class="{{ $formClass }}"
          @if($form->getAttribute('data-validate') !== false) data-validate="true" @endif
          @if($form->getAttribute('data-auto-save')) data-auto-save="{{ $form->getAttribute('data-auto-save') }}" @endif>
        
        @csrf
        @if($form->getMethod() !== 'GET' && $form->getMethod() !== 'POST')
            @method($form->getMethod())
        @endif

        @if($isMultiStep)
            {{-- Multi-step Sections --}}
            @foreach($steps as $index => $step)
                @php $stepNumber = $index + 1; @endphp
                <div class="form-step {{ $stepNumber === $currentStep ? 'block' : 'hidden' }}" 
                     data-step="{{ $stepNumber }}">
                    
                    @if($step['title'] ?? null)
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">{{ $step['title'] }}</h2>
                            @if($step['description'] ?? null)
                                <p class="mt-1 text-sm text-gray-600">{{ $step['description'] }}</p>
                            @endif
                        </div>
                    @endif

                    <div class="space-y-6">
                        @foreach($step['fields'] as $field)
                            @include('form::tailwind.fields.' . $field->getType(), ['field' => $field])
                        @endforeach
                    </div>
                </div>
            @endforeach

            {{-- Multi-step Navigation --}}
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <button type="button" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        onclick="previousStep()"
                        id="prevBtn"
                        {{ $currentStep <= 1 ? 'disabled' : '' }}>
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Previous
                </button>
                
                <div class="flex space-x-3">
                    <button type="button" 
                            class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            onclick="nextStep()"
                            id="nextBtn"
                            {{ $currentStep >= count($steps) ? 'style=display:none' : '' }}>
                        Next
                        <svg class="ml-2 -mr-1 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                            id="submitBtn"
                            {{ $currentStep < count($steps) ? 'style=display:none' : '' }}>
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        {{ $form->getSubmitText() ?? 'Submit' }}
                    </button>
                </div>
            </div>
        @else
            {{-- Single Form --}}
            <div class="space-y-6">
                @foreach($form->getFields() as $field)
                    @if($field->getType() === 'group')
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                            @if($field->getLabel())
                                <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                    {{ $field->getLabel() }}
                                </h3>
                            @endif
                            <div class="space-y-4">
                                @foreach($field->getFields() as $groupField)
                                    @include('form::tailwind.fields.' . $groupField->getType(), ['field' => $groupField])
                                @endforeach
                            </div>
                        </div>
                    @else
                        @include('form::tailwind.fields.' . $field->getType(), ['field' => $field])
                    @endif
                @endforeach
            </div>

            {{-- Single Form Submit --}}
            <div class="flex items-center justify-end pt-6 border-t border-gray-200">
                @if($form->getCancelUrl())
                    <a href="{{ $form->getCancelUrl() }}" 
                       class="mr-3 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </a>
                @endif
                
                <button type="submit" 
                        class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ $form->getSubmitText() ?? 'Submit' }}
                </button>
            </div>
        @endif
    </form>
</div>

@if($isMultiStep)
<script>
let currentStep = {{ $currentStep }};
const totalSteps = {{ count($steps) }};

function goToStep(step) {
    if (step > currentStep) return; // Can't go to future steps
    
    // Hide current step
    document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.add('hidden');
    
    // Show target step
    document.querySelector(`.form-step[data-step="${step}"]`).classList.remove('hidden');
    
    currentStep = step;
    updateNavigation();
}

function nextStep() {
    if (currentStep >= totalSteps) return;
    
    // Validate current step
    const currentStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
    const requiredFields = currentStepElement.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.checkValidity()) {
            isValid = false;
            field.reportValidity();
        }
    });
    
    if (!isValid) return;
    
    goToStep(currentStep + 1);
}

function previousStep() {
    if (currentStep <= 1) return;
    goToStep(currentStep - 1);
}

function updateNavigation() {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    
    // Update previous button
    prevBtn.disabled = currentStep <= 1;
    
    // Update next/submit buttons
    if (currentStep >= totalSteps) {
        nextBtn.style.display = 'none';
        submitBtn.style.display = 'inline-flex';
    } else {
        nextBtn.style.display = 'inline-flex';
        submitBtn.style.display = 'none';
    }
    
    // Update progress bar
    const progressBar = document.querySelector('.bg-indigo-600');
    if (progressBar) {
        const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
        progressBar.style.width = progress + '%';
    }
    
    // Update step counter
    const stepCounter = document.querySelector('.text-sm.text-gray-600');
    if (stepCounter) {
        stepCounter.textContent = `Step ${currentStep} of ${totalSteps}`;
    }
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey || e.metaKey) {
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            previousStep();
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            nextStep();
        }
    }
});
</script>
@endif
