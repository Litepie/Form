{{-- Bootstrap 5 Form Wrapper --}}
<form {!! $form->getAttributesString() !!}>
    @if($form->hasCsrf())
        @csrf
    @endif
    
    @if($form->hasMethodSpoofing())
        @method($form->getMethod())
    @endif

    @if($form->hasErrors())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading">
                <i class="bi bi-exclamation-triangle"></i>
                Please correct the following errors:
            </h5>
            <ul class="mb-0">
                @foreach($form->getErrors() as $field => $errors)
                    @foreach($errors as $error)
                        <li><strong>{{ $form->getField($field)->getLabel() }}:</strong> {{ $error }}</li>
                    @endforeach
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($form->isMultiStep())
        {{-- Multi-step progress indicator --}}
        <div class="form-steps mb-4">
            <div class="progress mb-3" style="height: 6px;">
                <div class="progress-bar" role="progressbar" 
                     style="width: {{ ($form->getCurrentStep() / $form->getTotalSteps()) * 100 }}%"
                     aria-valuenow="{{ $form->getCurrentStep() }}" 
                     aria-valuemin="0" 
                     aria-valuemax="{{ $form->getTotalSteps() }}">
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                @foreach($form->getSteps() as $step)
                    <div class="step-item {{ $step['number'] <= $form->getCurrentStep() ? 'active' : '' }}">
                        <div class="step-number">{{ $step['number'] }}</div>
                        <div class="step-title">{{ $step['title'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="form-fields">
        @foreach($form->getFields() as $field)
            @if(!$form->isMultiStep() || $field->getStep() == $form->getCurrentStep())
                {!! $field->render() !!}
            @endif
        @endforeach
    </div>

    @if($form->isMultiStep())
        <div class="form-navigation mt-4">
            <div class="d-flex justify-content-between">
                @if($form->getCurrentStep() > 1)
                    <button type="button" class="btn btn-outline-secondary btn-prev">
                        <i class="bi bi-arrow-left"></i> Previous
                    </button>
                @else
                    <div></div>
                @endif

                @if($form->getCurrentStep() < $form->getTotalSteps())
                    <button type="button" class="btn btn-primary btn-next">
                        Next <i class="bi bi-arrow-right"></i>
                    </button>
                @else
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Submit
                    </button>
                @endif
            </div>
        </div>
    @endif
</form>

@if($form->isAjax())
    <div class="form-loading d-none">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Submitting form...</p>
        </div>
    </div>
@endif
