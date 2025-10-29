@extends('layout.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Create Workspace & Complete Questionnaire</h4>
                        <p class="text-muted mb-0">Follow the steps to set up your workspace</p>
                    </div>
                    <div class="card-body form-steps">
                        <form id="workspace-wizard-form">
                            @csrf
                            <div class="row gy-5">
                                <!-- Left Sidebar - Steps Navigation -->
                                <div class="col-lg-3">
                                    <div class="nav flex-column custom-nav nav-pills" role="tablist"
                                        aria-orientation="vertical">
                                        <!-- Step 1: Workspace Info -->
                                        <button class="nav-link text-start active" id="step-workspace-tab"
                                            data-bs-toggle="pill" data-bs-target="#step-workspace" type="button"
                                            role="tab" aria-controls="step-workspace" aria-selected="true">
                                            <span class="step-title me-2">
                                                <i class="ri-building-line step-icon me-2"></i> Step 1
                                            </span>
                                            Workspace Info
                                        </button>

                                        <!-- Dynamic Questionnaire Steps -->
                                        @foreach ($questionnaireSections as $index => $section)
                                            <button class="nav-link text-start" id="step-{{ $section['slug'] }}-tab"
                                                data-bs-toggle="pill" data-bs-target="#step-{{ $section['slug'] }}"
                                                type="button" role="tab" aria-controls="step-{{ $section['slug'] }}"
                                                aria-selected="false">
                                                <span class="step-title me-2">
                                                    <i class="ri-questionnaire-line step-icon me-2"></i> Step
                                                    {{ $index + 2 }}
                                                </span>
                                                {{ $section['name'] }}
                                            </button>
                                        @endforeach

                                        <!-- Final Step: Review & Submit -->
                                        <button class="nav-link text-start" id="step-finish-tab" data-bs-toggle="pill"
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
                                    <div class="px-lg-4" style="height:80vh; overflow:hidden;">
                                        <div class="tab-content"
                                            style="height:calc(80vh - 80px); overflow-y:auto;">
                                            <!-- Step 1: Workspace Information -->
                                            <div class="tab-pane fade show active" id="step-workspace" role="tabpanel"
                                                aria-labelledby="step-workspace-tab">
                                                <div>
                                                    <h5>Workspace Information</h5>
                                                    <p class="text-muted">Enter your workspace details</p>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-12">
                                                        <label for="workspace_name" class="form-label">Workspace Name <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            id="workspace_name" name="name"
                                                            placeholder="Enter workspace name" required>
                                                        <div class="invalid-feedback">Please enter workspace name</div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label for="workspace_description"
                                                            class="form-label">Description</label>
                                                        <textarea class="form-control form-control-sm" id="workspace_description" name="description" rows="3"
                                                            placeholder="Brief description of your workspace"></textarea>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="workspace_type" class="form-label">Workspace
                                                            Type</label>
                                                        <select class="form-control form-control-sm select2"
                                                            id="workspace_type" name="type">
                                                            <option value="">Select Type</option>
                                                            <option value="personal">Personal</option>
                                                            <option value="team">Team</option>
                                                            <option value="enterprise">Enterprise</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="workspace_status" class="form-label">Status</label>
                                                        <select class="form-control form-control-sm select2"
                                                            id="workspace_status" name="status">
                                                            <option value="active" selected>Active</option>
                                                            <option value="inactive">Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-start gap-3 mt-4">
                                                    <button type="button"
                                                        class="btn btn-success btn-label right ms-auto nexttab"
                                                        data-nexttab="step-{{ $questionnaireSections[0]['slug'] ?? 'finish' }}-tab">
                                                        <i
                                                            class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                                                        Next: Questionnaire
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- Dynamic Questionnaire Steps -->
                                            @foreach ($questionnaireSections as $sectionIndex => $section)
                                                {{-- @dd($section['questions']) --}}
                                                <div class="tab-pane fade" id="step-{{ $section['slug'] }}" role="tabpanel"
                                                    aria-labelledby="step-{{ $section['slug'] }}-tab">
                                                    <div>
                                                        <h5>{{ $section['name'] }}</h5>
                                                        <p class="text-muted">
                                                            {{ $section['description'] ?? 'Please answer the following questions' }}
                                                        </p>
                                                    </div>

                                                    <div class="row g-3" id="questions-{{ $section['slug'] }}">
                                                        @foreach ($section['questions'] as $question)
                                                            <div class="col-md-12">
                                                                <label for="question-{{ $question->id }}"
                                                                    class="form-label">
                                                                    {{ $question->question }}
                                                                    @if ($question->is_required)
                                                                        <span class="text-danger">*</span>
                                                                    @endif
                                                                </label>

                                                                @if ($question->description)
                                                                    <p class="text-muted small">
                                                                        {{ $question->description }}</p>
                                                                @endif

                                                                @if ($question->type === 'text')
                                                                    <input type="text"
                                                                        class="form-control form-control-sm"
                                                                        id="question-{{ $question->id }}"
                                                                        name="answers[{{ $question->id }}]"
                                                                        {{ $question->is_required ? 'required' : '' }}>
                                                                @elseif($question->type === 'textarea')
                                                                    <textarea class="form-control form-control-sm" id="question-{{ $question->id }}"
                                                                        name="answers[{{ $question->id }}]" rows="4" {{ $question->is_required ? 'required' : '' }}></textarea>
                                                                @elseif($question->type === 'select')
                                                                    <select class="form-control form-control-sm select2"
                                                                        id="question-{{ $question->id }}"
                                                                        name="answers[{{ $question->id }}]"
                                                                        {{ $question->is_required ? 'required' : '' }}>
                                                                        <option value="">Select an option</option>
                                                                        @if ($question->options)
                                                                            @foreach ($question->options as $option)
                                                                                <option value="{{ $option }}">
                                                                                    {{ $option }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                @elseif($question->type === 'radio')
                                                                    @if ($question->options)
                                                                        <div class="row">

                                                                            @foreach ($question->options as $optIndex => $option)
                                                                                <div class="col-md-4">
                                                                                    <div class="form-check">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            name="answers[{{ $question->id }}]"
                                                                                            id="question-{{ $question->id }}-{{ $optIndex }}"
                                                                                            value="{{ $option }}"
                                                                                            {{ $question->is_required ? 'required' : '' }}>
                                                                                        <label class="form-check-label"
                                                                                            for="question-{{ $question->id }}-{{ $optIndex }}">
                                                                                            {{ $option }}
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                @elseif($question->type === 'checkbox')
                                                                    @if ($question->options)
                                                                        <div class="row">
                                                                            @foreach ($question->options as $optIndex => $option)
                                                                                <div class="col-md-4">
                                                                                    <div class="form-check">
                                                                                        <input class="form-check-input"
                                                                                            type="checkbox"
                                                                                            name="answers[{{ $question->id }}][]"
                                                                                            id="question-{{ $question->id }}-{{ $optIndex }}"
                                                                                            value="{{ $option }}">
                                                                                        <label class="form-check-label"
                                                                                            for="question-{{ $question->id }}-{{ $optIndex }}">
                                                                                            {{ $option }}
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                @endif

                                                                <div class="invalid-feedback">This field is required</div>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    <div class="d-flex align-items-start gap-3 mt-4">
                                                        <button type="button" class="btn btn-light btn-label previestab"
                                                            data-previous="{{ $sectionIndex > 0 ? 'step-' . $questionnaireSections[$sectionIndex - 1]['slug'] . '-tab' : 'step-workspace-tab' }}">
                                                            <i
                                                                class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                                            Back
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-success btn-label right ms-auto nexttab"
                                                            data-nexttab="{{ isset($questionnaireSections[$sectionIndex + 1]) ? 'step-' . $questionnaireSections[$sectionIndex + 1]['slug'] . '-tab' : 'step-finish-tab' }}">
                                                            <i
                                                                class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                                                            {{ isset($questionnaireSections[$sectionIndex + 1]) ? 'Next Section' : 'Review & Submit' }}
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach

                                            <!-- Final Step: Review & Submit -->
                                            <div class="tab-pane fade" id="step-finish" role="tabpanel"
                                                aria-labelledby="step-finish-tab">
                                                <div>
                                                    <h5>Review Your Information</h5>
                                                    <p class="text-muted">Please review all information before submitting
                                                    </p>
                                                </div>

                                                <div id="review-content" class="mb-4">
                                                    <!-- Review content will be populated via JavaScript -->
                                                </div>

                                                <div class="d-flex align-items-start gap-3 mt-4">
                                                    <button type="button" class="btn btn-light btn-label previestab"
                                                        data-previous="step-{{ end($questionnaireSections)['slug'] ?? 'workspace' }}-tab">
                                                        <i
                                                            class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                                        Back
                                                    </button>
                                                    <button type="button" id="submit-wizard"
                                                        class="btn btn-primary btn-label right ms-auto">
                                                        <i class="ri-check-line label-icon align-middle fs-16 ms-2"></i>
                                                        Submit Workspace
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Sidebar - Progress Summary -->
                                <div class="col-lg-3">
                                    <div class="card border">
                                        <div class="card-header bg-light">
                                            <h5 class="fs-14 mb-0">
                                                <i class="ri-progress-3-line align-middle me-2"></i> Progress
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="text-muted">Completion</span>
                                                    <span id="progress-percentage"
                                                        class="text-primary fw-semibold">0%</span>
                                                </div>
                                                <div class="progress" style="height: 8px;">
                                                    <div id="progress-bar" class="progress-bar" role="progressbar"
                                                        style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>

                                            <div class="mt-4">
                                                <h6 class="fs-13 mb-3">Steps Completed</h6>
                                                <ul class="list-unstyled mb-0">
                                                    <li class="mb-2" id="progress-workspace">
                                                        <i class="ri-checkbox-blank-circle-line text-muted me-2"></i>
                                                        <span class="text-muted">Workspace Info</span>
                                                    </li>
                                                    @foreach ($questionnaireSections as $section)
                                                        <li class="mb-2" id="progress-{{ $section['slug'] }}">
                                                            <i class="ri-checkbox-blank-circle-line text-muted me-2"></i>
                                                            <span class="text-muted">{{ $section['name'] }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>

                                            <div class="alert alert-info mt-4" role="alert">
                                                <strong>Tip:</strong> All fields marked with <span
                                                    class="text-danger">*</span> are required.
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

    {{-- @push('scripts') --}}
@section('script')
    <script src="{{ asset('js/workspace-wizard.js') }}"></script>
    <script>
        // Workspace Wizard JavaScript
        (function() {
            'use strict';

            // Initialize variables
            let currentStep = 1;
            const totalSteps = document.querySelectorAll('.nav-link[data-bs-toggle="pill"]').length;
            const form = document.getElementById('workspace-wizard-form');

            // Initialize wizard on page load
            document.addEventListener('DOMContentLoaded', function() {
                initializeWizard();
                attachEventListeners();
                updateProgress();
            });

            /**
             * Initialize wizard functionality
             */
            function initializeWizard() {
                // First step is already active by default in HTML
                // No need to do anything special here
                console.log('Wizard initialized with ' + totalSteps + ' steps');
            }

            /**
             * Attach event listeners to buttons
             */
            function attachEventListeners() {
                // Use event delegation for Next buttons
                document.addEventListener('click', function(e) {
                    const nextBtn = e.target.closest('.nexttab');
                    if (nextBtn) {
                        e.preventDefault();
                        const currentTab = nextBtn.closest('.tab-pane');

                        if (validateCurrentStep(currentTab)) {
                            const nextTabId = nextBtn.getAttribute('data-nexttab');
                            goToTab(nextTabId);
                            markStepComplete(currentTab.id);
                        }
                    }

                    // Handle Previous buttons
                    const prevBtn = e.target.closest('.previestab');
                    if (prevBtn) {
                        e.preventDefault();
                        const prevTabId = prevBtn.getAttribute('data-previous');
                        goToTab(prevTabId);
                    }

                    // Handle Submit button
                    if (e.target.id === 'submit-wizard' || e.target.closest('#submit-wizard')) {
                        e.preventDefault();
                        handleFormSubmission();
                    }
                });

                // Track form changes for auto-save
                form.addEventListener('change', function() {
                    updateProgress();
                });
            }

            /**
             * Navigate to a specific tab
             */
            function goToTab(tabId) {
                const tab = document.getElementById(tabId);
                if (tab) {
                    const bsTab = new bootstrap.Tab(tab);
                    bsTab.show();

                    // Scroll to top
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });

                    // Update progress
                    updateProgress();

                    // If final step, populate review
                    if (tabId === 'step-finish-tab') {
                        populateReview();
                    }
                }
            }

            /**
             * Validate current step before proceeding
             */
            function validateCurrentStep(stepPane) {
                const inputs = stepPane.querySelectorAll('input[required], select[required], textarea[required]');
                let isValid = true;

                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        input.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    }
                });

                // Check radio buttons
                const radioGroups = {};
                stepPane.querySelectorAll('input[type="radio"][required]').forEach(radio => {
                    const name = radio.name;
                    if (!radioGroups[name]) {
                        radioGroups[name] = false;
                    }
                    if (radio.checked) {
                        radioGroups[name] = true;
                    }
                });

                Object.keys(radioGroups).forEach(groupName => {
                    if (!radioGroups[groupName]) {
                        isValid = false;
                        const radioInputs = stepPane.querySelectorAll(`input[name="${groupName}"]`);
                        radioInputs.forEach(input => input.classList.add('is-invalid'));
                    }
                });

                if (!isValid) {
                    showNotification('error', 'Please fill in all required fields');
                }

                return isValid;
            }

            /**
             * Mark a step as complete
             */
            function markStepComplete(stepId) {
                const stepSlug = stepId.replace('step-', '');
                const progressItem = document.getElementById(`progress-${stepSlug}`);

                if (progressItem) {
                    const icon = progressItem.querySelector('i');
                    const span = progressItem.querySelector('span');

                    icon.className = 'ri-checkbox-circle-fill text-success me-2';
                    span.className = 'text-success';
                }

                // Update nav pill
                const navButton = document.getElementById(`${stepId}-tab`);
                if (navButton) {
                    navButton.classList.add('done');
                    const icon = navButton.querySelector('.step-icon');
                    if (icon) {
                        icon.className = 'ri-checkbox-circle-fill step-icon me-2';
                    }
                }
            }

            /**
             * Update progress bar
             */
            function updateProgress() {
                const completedSteps = document.querySelectorAll('.nav-link.done').length;
                const activeSteps = document.querySelectorAll('.nav-link.active').length;
                const progress = Math.round(((completedSteps + (activeSteps > 0 ? 0.5 : 0)) / totalSteps) * 100);

                document.getElementById('progress-bar').style.width = progress + '%';
                document.getElementById('progress-bar').setAttribute('aria-valuenow', progress);
                document.getElementById('progress-percentage').textContent = progress + '%';
            }

            /**
             * Populate review section
             */
            function populateReview() {
                const reviewContent = document.getElementById('review-content');
                let html = '';

                // Workspace Information
                html += '<div class="card mb-3">';
                html += '<div class="card-header bg-light"><h6 class="mb-0">Workspace Information</h6></div>';
                html += '<div class="card-body">';
                html += '<dl class="row mb-0">';

                const workspaceName = document.getElementById('workspace_name').value;
                const workspaceDesc = document.getElementById('workspace_description').value;
                const workspaceType = document.getElementById('workspace_type').value;
                const workspaceStatus = document.getElementById('workspace_status').value;

                html +=
                    `<dt class="col-sm-4">Workspace Name:</dt><dd class="col-sm-8">${workspaceName || 'Not provided'}</dd>`;
                html +=
                    `<dt class="col-sm-4">Description:</dt><dd class="col-sm-8">${workspaceDesc || 'Not provided'}</dd>`;
                html += `<dt class="col-sm-4">Type:</dt><dd class="col-sm-8">${workspaceType || 'Not selected'}</dd>`;
                html +=
                    `<dt class="col-sm-4">Status:</dt><dd class="col-sm-8"><span class="badge bg-${workspaceStatus === 'active' ? 'success' : 'secondary'}">${workspaceStatus}</span></dd>`;

                html += '</dl></div></div>';

                // Questionnaire Responses
                const questionElements = document.querySelectorAll('[id^="questions-"]');
                questionElements.forEach(section => {
                    const sectionId = section.id.replace('questions-', '');
                    const sectionTitle = document.querySelector(`[data-bs-target="#step-${sectionId}"]`)
                        ?.textContent.trim() || sectionId;

                    html += '<div class="card mb-3">';
                    html += `<div class="card-header bg-light"><h6 class="mb-0">${sectionTitle}</h6></div>`;
                    html += '<div class="card-body">';
                    html += '<dl class="row mb-0">';

                    const questions = section.querySelectorAll('.col-md-12');
                    questions.forEach(questionDiv => {
                        const label = questionDiv.querySelector('label')?.textContent.replace('*', '')
                            .trim();
                        const input = questionDiv.querySelector('input, select, textarea');

                        if (input && label) {
                            let answer = '';

                            if (input.type === 'checkbox') {
                                const checkedBoxes = questionDiv.querySelectorAll(
                                    'input[type="checkbox"]:checked');
                                answer = Array.from(checkedBoxes).map(cb => cb.value).join(', ') ||
                                    'Not answered';
                            } else if (input.type === 'radio') {
                                const checkedRadio = questionDiv.querySelector(
                                    'input[type="radio"]:checked');
                                answer = checkedRadio ? checkedRadio.value : 'Not answered';
                            } else {
                                answer = input.value || 'Not answered';
                            }

                            html +=
                                `<dt class="col-sm-5">${label}</dt><dd class="col-sm-7">${answer}</dd>`;
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
                // Show loading state
                const submitBtn = document.getElementById('submit-wizard');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Submitting...';

                // Collect all form data
                const formData = new FormData(form);

                // Send AJAX request
                fetch('/workspaces', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showSuccessPage(data.data.workspace);
                        } else {
                            throw new Error(data.message || 'Submission failed');
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
             * Show success page after submission
             */
            function showSuccessPage(workspace) {
                console.log(workspace);

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
                <h5 class="text-success">Workspace Created Successfully!</h5>
                <p class="text-muted">Your workspace "${workspace.name}" has been created and questionnaire responses saved.</p>
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

                // Update progress to 100%
                document.getElementById('progress-bar').style.width = '100%';
                document.getElementById('progress-percentage').textContent = '100%';

                // Show success notification
                showNotification('success', 'Workspace created successfully!');
            }

            /**
             * Show notification
             */
            function showNotification(type, message) {
                // Use your preferred notification library (toastr, sweetalert, etc.)
                // This is a simple alert fallback
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



        })();
    </script>
    {{-- @endpush --}}
@endsection
@endsection
