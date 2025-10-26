@extends('layout.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Edit Workspace & Update Questionnaire</h4>
                    <p class="text-muted mb-0">Update your workspace information and questionnaire responses</p>
                </div>
                <div class="card-body form-steps">
                    <form id="workspace-wizard-form">
                        @csrf
                        @method('PUT')
                        <div class="row gy-5">
                            <!-- Left Sidebar - Steps Navigation -->
                            <div class="col-lg-3">
                                <div class="nav flex-column custom-nav nav-pills" role="tablist" aria-orientation="vertical">
                                    <!-- Step 1: Workspace Info -->
                                    <button class="nav-link active" id="step-workspace-tab" data-bs-toggle="pill"
                                        data-bs-target="#step-workspace" type="button" role="tab"
                                        aria-controls="step-workspace" aria-selected="true">
                                        <span class="step-title me-2">
                                            <i class="ri-building-line step-icon me-2"></i> Step 1
                                        </span>
                                        Workspace Info
                                    </button>

                                    <!-- Dynamic Questionnaire Steps -->
                                    @foreach($questionnaireSections as $index => $section)
                                    <button class="nav-link" id="step-{{ $section['slug'] }}-tab" data-bs-toggle="pill"
                                        data-bs-target="#step-{{ $section['slug'] }}" type="button" role="tab"
                                        aria-controls="step-{{ $section['slug'] }}" aria-selected="false">
                                        <span class="step-title me-2">
                                            <i class="ri-questionnaire-line step-icon me-2"></i> Step {{ $index + 2 }}
                                        </span>
                                        {{ $section['name'] }}
                                    </button>
                                    @endforeach

                                    <!-- Final Step: Review & Submit -->
                                    <button class="nav-link" id="step-finish-tab" data-bs-toggle="pill"
                                        data-bs-target="#step-finish" type="button" role="tab"
                                        aria-controls="step-finish" aria-selected="false">
                                        <span class="step-title me-2">
                                            <i class="ri-checkbox-circle-line step-icon me-2"></i> Final Step
                                        </span>
                                        Review & Submit
                                    </button>
                                </div>
                            </div>

                            <!-- Middle Content Area - Form Steps -->
                            <div class="col-lg-6">
                                <div class="px-lg-4">
                                    <div class="tab-content">
                                        <!-- Step 1: Workspace Information -->
                                        <div class="tab-pane fade show active" id="step-workspace" role="tabpanel"
                                            aria-labelledby="step-workspace-tab">
                                            <div>
                                                <h5>Workspace Information</h5>
                                                <p class="text-muted">Update your workspace details</p>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-12">
                                                    <label for="workspace_name" class="form-label">Workspace Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="workspace_name"
                                                        name="workspace_name" placeholder="Enter workspace name"
                                                        value="{{ old('workspace_name', $record->first_name) }}" required>
                                                    <div class="invalid-feedback">Please enter workspace name</div>
                                                </div>

                                                <div class="col-md-12">
                                                    <label for="workspace_description" class="form-label">Description</label>
                                                    <textarea class="form-control" id="workspace_description"
                                                        name="workspace_description" rows="3"
                                                        placeholder="Brief description of your workspace">{{ old('workspace_description', $record->address) }}</textarea>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="workspace_type" class="form-label">Workspace Type</label>
                                                    <select class="form-select" id="workspace_type" name="workspace_type">
                                                        <option value="">Select Type</option>
                                                        <option value="personal" {{ old('workspace_type', $record->workspace_type) == 'personal' ? 'selected' : '' }}>Personal</option>
                                                        <option value="team" {{ old('workspace_type', $record->workspace_type) == 'team' ? 'selected' : '' }}>Team</option>
                                                        <option value="enterprise" {{ old('workspace_type', $record->workspace_type) == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="workspace_status" class="form-label">Status</label>
                                                    <select class="form-select" id="workspace_status" name="workspace_status">
                                                        <option value="active" {{ old('workspace_status', $record->status) == 'active' ? 'selected' : '' }}>Active</option>
                                                        <option value="inactive" {{ old('workspace_status', $record->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-start gap-3 mt-4">
                                                <button type="button" class="btn btn-success btn-label right ms-auto nexttab"
                                                    data-nexttab="step-{{ $questionnaireSections[0]['slug'] ?? 'finish' }}-tab">
                                                    <i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                                                    Next: Questionnaire
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Dynamic Questionnaire Steps -->
                                        @foreach($questionnaireSections as $sectionIndex => $section)
                                        <div class="tab-pane fade" id="step-{{ $section['slug'] }}" role="tabpanel"
                                            aria-labelledby="step-{{ $section['slug'] }}-tab">
                                            <div>
                                                <h5>{{ $section['name'] }}</h5>
                                                <p class="text-muted">{{ $section['description'] ?? 'Please answer the following questions' }}</p>
                                            </div>

                                            <div class="row g-3" id="questions-{{ $section['slug'] }}">
                                                @foreach($section['questions'] as $question)
                                                <div class="col-md-12">
                                                    <label for="question-{{ $question->id }}" class="form-label">
                                                        {{ $question->question }}
                                                        @if($question->is_required)
                                                        <span class="text-danger">*</span>
                                                        @endif
                                                    </label>

                                                    @if($question->description)
                                                    <p class="text-muted small">{{ $question->description }}</p>
                                                    @endif

                                                    @php
                                                        $existingAnswer = $existingResponses[$question->id] ?? null;
                                                    @endphp

                                                    @if($question->type === 'text')
                                                        <input type="text"
                                                            class="form-control"
                                                            id="question-{{ $question->id }}"
                                                            name="answers[{{ $question->id }}]"
                                                            value="{{ old('answers.'.$question->id, $existingAnswer) }}"
                                                            {{ $question->is_required ? 'required' : '' }}>

                                                    @elseif($question->type === 'textarea')
                                                        <textarea
                                                            class="form-control"
                                                            id="question-{{ $question->id }}"
                                                            name="answers[{{ $question->id }}]"
                                                            rows="4"
                                                            {{ $question->is_required ? 'required' : '' }}>{{ old('answers.'.$question->id, $existingAnswer) }}</textarea>

                                                    @elseif($question->type === 'select')
                                                        <select
                                                            class="form-select"
                                                            id="question-{{ $question->id }}"
                                                            name="answers[{{ $question->id }}]"
                                                            {{ $question->is_required ? 'required' : '' }}>
                                                            <option value="">Select an option</option>
                                                            @foreach(json_decode($question->options, true) as $option)
                                                                <option value="{{ $option }}" {{ old('answers.'.$question->id, $existingAnswer) == $option ? 'selected' : '' }}>
                                                                    {{ $option }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                    @elseif($question->type === 'radio')
                                                        @foreach(json_decode($question->options, true) as $optionIndex => $option)
                                                        <div class="form-check">
                                                            <input class="form-check-input"
                                                                type="radio"
                                                                name="answers[{{ $question->id }}]"
                                                                id="question-{{ $question->id }}-{{ $optionIndex }}"
                                                                value="{{ $option }}"
                                                                {{ old('answers.'.$question->id, $existingAnswer) == $option ? 'checked' : '' }}
                                                                {{ $question->is_required ? 'required' : '' }}>
                                                            <label class="form-check-label" for="question-{{ $question->id }}-{{ $optionIndex }}">
                                                                {{ $option }}
                                                            </label>
                                                        </div>
                                                        @endforeach

                                                    @elseif($question->type === 'checkbox')
                                                        @php
                                                            $existingAnswerArray = is_array($existingAnswer) ? $existingAnswer : [];
                                                        @endphp
                                                        @foreach(json_decode($question->options, true) as $optionIndex => $option)
                                                        <div class="form-check">
                                                            <input class="form-check-input"
                                                                type="checkbox"
                                                                name="answers[{{ $question->id }}][]"
                                                                id="question-{{ $question->id }}-{{ $optionIndex }}"
                                                                value="{{ $option }}"
                                                                {{ in_array($option, old('answers.'.$question->id, $existingAnswerArray)) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="question-{{ $question->id }}-{{ $optionIndex }}">
                                                                {{ $option }}
                                                            </label>
                                                        </div>
                                                        @endforeach

                                                    @elseif($question->type === 'date')
                                                        <input type="date"
                                                            class="form-control"
                                                            id="question-{{ $question->id }}"
                                                            name="answers[{{ $question->id }}]"
                                                            value="{{ old('answers.'.$question->id, $existingAnswer) }}"
                                                            {{ $question->is_required ? 'required' : '' }}>

                                                    @elseif($question->type === 'number')
                                                        <input type="number"
                                                            class="form-control"
                                                            id="question-{{ $question->id }}"
                                                            name="answers[{{ $question->id }}]"
                                                            value="{{ old('answers.'.$question->id, $existingAnswer) }}"
                                                            {{ $question->is_required ? 'required' : '' }}>

                                                    @elseif($question->type === 'email')
                                                        <input type="email"
                                                            class="form-control"
                                                            id="question-{{ $question->id }}"
                                                            name="answers[{{ $question->id }}]"
                                                            value="{{ old('answers.'.$question->id, $existingAnswer) }}"
                                                            {{ $question->is_required ? 'required' : '' }}>
                                                    @endif

                                                    <div class="invalid-feedback">
                                                        This field is required
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>

                                            <div class="d-flex align-items-start gap-3 mt-4">
                                                <button type="button" class="btn btn-light btn-label previestab"
                                                    data-previous="step-{{ $sectionIndex > 0 ? $questionnaireSections[$sectionIndex - 1]['slug'] : 'workspace' }}-tab">
                                                    <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                                    Previous
                                                </button>
                                                <button type="button" class="btn btn-success btn-label right ms-auto nexttab"
                                                    data-nexttab="step-{{ $questionnaireSections[$sectionIndex + 1]['slug'] ?? 'finish' }}-tab">
                                                    <i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                                                    {{ isset($questionnaireSections[$sectionIndex + 1]) ? 'Next Section' : 'Review' }}
                                                </button>
                                            </div>
                                        </div>
                                        @endforeach

                                        <!-- Final Step: Review & Submit -->
                                        <div class="tab-pane fade" id="step-finish" role="tabpanel" aria-labelledby="step-finish-tab">
                                            <div>
                                                <h5>Review & Submit</h5>
                                                <p class="text-muted">Please review your information before updating</p>
                                            </div>

                                            <div id="review-content">
                                                <!-- Review content will be populated by JavaScript -->
                                            </div>

                                            <div class="d-flex align-items-start gap-3 mt-4">
                                                <button type="button" class="btn btn-light btn-label previestab"
                                                    data-previous="step-{{ $questionnaireSections[count($questionnaireSections) - 1]['slug'] ?? 'workspace' }}-tab">
                                                    <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                                    Previous
                                                </button>
                                                <button type="button" class="btn btn-success btn-label right ms-auto" id="submit-wizard">
                                                    <i class="ri-check-line label-icon align-middle fs-16 ms-2"></i>
                                                    Update Workspace
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Sidebar - Progress -->
                            <div class="col-lg-3">
                                <div class="sticky-side-div">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">Progress</h5>
                                            <div class="progress animated-progress mb-3" style="height: 20px;">
                                                <div class="progress-bar bg-success" role="progressbar" id="progress-bar"
                                                    style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                    <span id="progress-percentage">0%</span>
                                                </div>
                                            </div>
                                            <p class="text-muted mb-0">Complete all steps to update your workspace</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';

    const form = document.getElementById('workspace-wizard-form');
    const workspaceId = {{ $record->id }};
    const totalSteps = {{ count($questionnaireSections) + 2 }}; // workspace + questionnaire sections + finish

    /**
     * Initialize the wizard
     */
    function init() {
        setupNavigationButtons();
        setupSubmitButton();
        updateProgress();
    }

    /**
     * Setup navigation buttons (Next/Previous)
     */
    function setupNavigationButtons() {
        document.querySelectorAll('.nexttab').forEach(button => {
            button.addEventListener('click', function() {
                if (validateCurrentStep()) {
                    const nextTabId = this.getAttribute('data-nexttab');
                    if (nextTabId === 'step-finish-tab') {
                        populateReviewSection();
                    }
                    activateTab(nextTabId);
                    updateProgress();
                }
            });
        });

        document.querySelectorAll('.previestab').forEach(button => {
            button.addEventListener('click', function() {
                const prevTabId = this.getAttribute('data-previous');
                activateTab(prevTabId);
                updateProgress();
            });
        });
    }

    /**
     * Setup submit button
     */
    function setupSubmitButton() {
        document.getElementById('submit-wizard').addEventListener('click', function(e) {
            e.preventDefault();
            if (form.checkValidity()) {
                handleFormSubmission();
            } else {
                form.reportValidity();
            }
        });
    }

    /**
     * Validate current step
     */
    function validateCurrentStep() {
        const activePane = document.querySelector('.tab-pane.active');
        const inputs = activePane.querySelectorAll('input, select, textarea');
        let isValid = true;

        inputs.forEach(input => {
            if (!input.checkValidity()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });

        return isValid;
    }

    /**
     * Activate a specific tab
     */
    function activateTab(tabId) {
        const tab = document.getElementById(tabId);
        if (tab) {
            const bsTab = new bootstrap.Tab(tab);
            bsTab.show();
        }
    }

    /**
     * Update progress bar
     */
    function updateProgress() {
        const activeTabIndex = Array.from(document.querySelectorAll('.nav-link')).findIndex(tab => tab.classList.contains('active'));
        const progress = Math.round(((activeTabIndex + 1) / totalSteps) * 100);

        document.getElementById('progress-bar').style.width = progress + '%';
        document.getElementById('progress-percentage').textContent = progress + '%';
    }

    /**
     * Populate review section
     */
    function populateReviewSection() {
        const reviewContent = document.getElementById('review-content');
        let html = '';

        // Workspace Info
        html += '<div class="card mb-3"><div class="card-body">';
        html += '<h6 class="card-title">Workspace Information</h6>';
        html += '<dl class="row mb-0">';
        html += `<dt class="col-sm-5">Workspace Name</dt><dd class="col-sm-7">${document.getElementById('workspace_name').value || 'Not provided'}</dd>`;
        html += `<dt class="col-sm-5">Description</dt><dd class="col-sm-7">${document.getElementById('workspace_description').value || 'Not provided'}</dd>`;
        html += `<dt class="col-sm-5">Type</dt><dd class="col-sm-7">${document.getElementById('workspace_type').value || 'Not provided'}</dd>`;
        html += `<dt class="col-sm-5">Status</dt><dd class="col-sm-7">${document.getElementById('workspace_status').value || 'Not provided'}</dd>`;
        html += '</dl></div></div>';

        // Questionnaire sections
        document.querySelectorAll('[id^="step-"]:not(#step-workspace):not(#step-finish)').forEach(section => {
            const sectionTitle = section.querySelector('h5')?.textContent || 'Section';
            html += '<div class="card mb-3"><div class="card-body">';
            html += `<h6 class="card-title">${sectionTitle}</h6>`;
            html += '<dl class="row mb-0">';

            const questions = section.querySelectorAll('.col-md-12');
            questions.forEach(questionDiv => {
                const label = questionDiv.querySelector('label')?.textContent.replace('*', '').trim();
                const input = questionDiv.querySelector('input, select, textarea');

                if (input && label) {
                    let answer = '';

                    if (input.type === 'checkbox') {
                        const checkedBoxes = questionDiv.querySelectorAll('input[type="checkbox"]:checked');
                        answer = Array.from(checkedBoxes).map(cb => cb.value).join(', ') || 'Not answered';
                    } else if (input.type === 'radio') {
                        const checkedRadio = questionDiv.querySelector('input[type="radio"]:checked');
                        answer = checkedRadio ? checkedRadio.value : 'Not answered';
                    } else {
                        answer = input.value || 'Not answered';
                    }

                    html += `<dt class="col-sm-5">${label}</dt><dd class="col-sm-7">${answer}</dd>`;
                }
            });

            html += '</dl></div></div>';
        });

        reviewContent.innerHTML = html;
    }

    /**
     * Handle form submission
     */
    function handleFormSubmission() {
        const submitBtn = document.getElementById('submit-wizard');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Updating...';

        const formData = new FormData(form);

        fetch(`/workspaces/${workspaceId}/update-with-questionnaire`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'X-HTTP-Method-Override': 'PUT',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessPage(data.workspace);
            } else {
                throw new Error(data.message || 'Update failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', error.message || 'An error occurred. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    }

    /**
     * Show success page
     */
    function showSuccessPage(workspace) {
        const finishPane = document.getElementById('step-finish');
        finishPane.innerHTML = `
            <div class="text-center pt-4 pb-2">
                <div class="mb-4">
                    <div class="avatar-xl mx-auto">
                        <div class="avatar-title bg-success-subtle text-success rounded-circle">
                            <i class="ri-checkbox-circle-line" style="font-size: 48px;"></i>
                        </div>
                    </div>
                </div>
                <h5 class="text-success">Workspace Updated Successfully!</h5>
                <p class="text-muted">Your workspace "${workspace.name}" has been updated.</p>
                <div class="mt-4">
                    <a href="/workspaces/${workspace.id}" class="btn btn-primary me-2">
                        <i class="ri-eye-line me-1"></i> View Workspace
                    </a>
                    <a href="/workspaces" class="btn btn-light">
                        <i class="ri-list-check me-1"></i> View All Workspaces
                    </a>
                </div>
            </div>
        `;

        document.getElementById('progress-bar').style.width = '100%';
        document.getElementById('progress-percentage').textContent = '100%';
        showNotification('success', 'Workspace updated successfully!');
    }

    /**
     * Show notification
     */
    function showNotification(type, message) {
        if (typeof toastr !== 'undefined') {
            toastr[type](message);
        } else if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type,
                title: type === 'success' ? 'Success!' : 'Error!',
                text: message,
                timer: 3000
            });
        } else {
            alert(message);
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', init);
})();
</script>
@endsection
