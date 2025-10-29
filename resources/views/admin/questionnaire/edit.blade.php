@extends('layout.master')
@section('css')
    <style>
        .question-item {
            transition: all 0.3s ease;
        }

        .question-item:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        .options-list .input-group {
            transition: all 0.2s ease;
        }

        .bg-soft-primary {
            background-color: rgba(13, 110, 253, 0.1);
        }
    </style>
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0 float-start">Edit {{ $title }}</h5>
                        <a href="{{ route($url . '.index') }}" class="btn btn-sm btn-secondary float-end">
                            <i class="ri-arrow-left-line me-1"></i> Back to List
                        </a>
                    </div>
                    <div class="card-body">
                        <form class="questionnaire-form" id="edit-questionnaire">
                            @csrf
                            @method('PUT')

                            <!-- Basic Information -->
                            <div class="card mb-3">
                                <div class="card-header bg-soft-primary">
                                    <h6 class="mb-0 float-start">Basic Information</h6>
                                    <a href="{{ route($url . '.index') }}" class="btn btn-sm float-end btn-outline-danger">
                                        <i class="ri-close-line me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-sm float-end me-2 btn-primary">
                                        <i class="ri-save-line me-1"></i> Update Questionnaire
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Section <span class="text-danger">*</span> <a
                                                    href="javascript:"
                                                    onclick="getAddRecord('{{ url('sections/create?select_id=section_id') }}','#addModel')"
                                                    class="me-2"><i style="font-size: medium;"
                                                        class="ri-add-circle-line me-2"></i>
                                                </a></label>
                                            <select class="form-control form-control-sm select2" id="section_id"
                                                name="section_id" required>
                                                <option value="">Select Section</option>
                                                @foreach (sections() as $section)
                                                    <option value="{{ $section->id }}" @selected($record->section_id == $section->id)>
                                                        {{ str_replace('_', ' ', $section->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm" name="title"
                                                placeholder="e.g., Customer Satisfaction Survey"
                                                value="{{ $record->title }}" required />
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control form-control-sm" name="description" rows="3"
                                                placeholder="Brief description of what this questionnaire is about...">{{ $record->description }}</textarea>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Questions Builder -->
                            <div class="card mb-3">
                                <div class="card-header bg-soft-primary d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Questions ({{ $record->questions->count() }})</h6>
                                    <button type="button" class="btn btn-sm btn-primary" id="addQuestionBtn">
                                        <i class="ri-add-line me-1"></i> Add Question
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div id="questionsContainer" class="questions-list">
                                        <!-- Existing questions will be loaded here -->
                                        @if ($record->questions->isEmpty())
                                            <div class="text-center text-muted py-4" id="noQuestionsMsg">
                                                <i class="ri-questionnaire-line" style="font-size: 3rem;"></i>
                                                <p class="mt-2">No questions added yet. Click "Add Question" to get
                                                    started.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Settings -->
                            <div class="card mb-3">
                                <div class="card-header bg-soft-primary">
                                    <h6 class="mb-0">Settings</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="allowAnonymous"
                                                    name="allow_anonymous" {{ $record->allow_anonymous ? 'checked' : '' }}>
                                                <label class="form-check-label" for="allowAnonymous">
                                                    Allow Anonymous Responses
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="multipleResponses"
                                                    name="allow_multiple_responses"
                                                    {{ $record->allow_multiple_responses ? 'checked' : '' }}>
                                                <label class="form-check-label" for="multipleResponses">
                                                    Allow Multiple Responses
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="showProgress"
                                                    name="show_progress" {{ $record->show_progress ? 'checked' : '' }}>
                                                <label class="form-check-label" for="showProgress">
                                                    Show Progress Bar
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="randomizeQuestions"
                                                    name="randomize_questions"
                                                    {{ $record->randomize_questions ? 'checked' : '' }}>
                                                <label class="form-check-label" for="randomizeQuestions">
                                                    Randomize Question Order
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control form-control-sm" name="status" required>
                                                <option value="active"
                                                    {{ $record->status == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive"
                                                    {{ $record->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                        </form>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div>

    <!-- Question Template -->
    <template id="questionTemplate">
        <div class="question-item card mb-3" data-question-index="" data-question-id="">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="question-number badge bg-primary">Q1</span>
                    <span class="question-type-badge badge bg-info"></span>
                </div>
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-secondary move-up" title="Move Up">
                        <i class="ri-arrow-up-line"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary move-down" title="Move Down">
                        <i class="ri-arrow-down-line"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger remove-question" title="Remove">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Question Text <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm question-text"
                            name="questions[][question]" placeholder="Enter your question here..." required />
                        <input type="hidden" class="question-id" name="questions[][id]" />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Question Type <span class="text-danger">*</span></label>
                        <select class="form-control form-control-sm question-type" name="questions[][type]" required>
                            <option value="text">Short Text</option>
                            <option value="textarea">Long Text</option>
                            <option value="radio">Multiple Choice (Single)</option>
                            <option value="checkbox">Multiple Choice (Multiple)</option>
                            <option value="select">Dropdown</option>
                            <option value="rating">Star Rating</option>
                            <option value="scale">Scale (1-10)</option>
                            <option value="date">Date</option>
                            <option value="file">File Upload</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description (Optional)</label>
                        <input type="text" class="form-control form-control-sm question-description"
                            name="questions[][description]" placeholder="Add helper text or instructions..." />
                    </div>

                    <!-- Options Container -->
                    <div class="col-md-12 mb-3 options-container" style="display: none;">
                        <label class="form-label">Options <span class="text-danger">*</span></label>
                        <div class="options-list"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary add-option">
                            <i class="ri-add-line me-1"></i> Add Option
                        </button>
                        <input type="hidden" class="question-options" name="questions[][options]" />
                    </div>

                    <!-- Additional Settings -->
                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input question-required" type="checkbox"
                                name="questions[][is_required]" checked>
                            <label class="form-check-label">
                                Required Question
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <div id="addRecord">

    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            let questionIndex = 0;

            // Existing questions data from server
            const existingQuestions = @json($record->questions);

            // Load existing questions
            loadExistingQuestions();

            function loadExistingQuestions() {
                if (existingQuestions.length === 0) {
                    $('#noQuestionsMsg').show();
                    return;
                }

                $('#noQuestionsMsg').hide();

                existingQuestions.forEach((question, index) => {
                    addQuestion(question);
                });
            }

            // Add Question Button
            $('#addQuestionBtn').on('click', function() {
                addQuestion();
            });

            // Add Question Function
            function addQuestion(existingData = null) {
                const template = $('#questionTemplate').html();
                const $question = $(template);

                questionIndex++;
                $question.attr('data-question-index', questionIndex);
                $question.find('.question-number').text('Q' + questionIndex);

                // If editing existing question
                if (existingData) {
                    $question.attr('data-question-id', existingData.id);
                    $question.find('.question-id').val(existingData.id);
                    $question.find('.question-text').val(existingData.question);
                    $question.find('.question-type').val(existingData.type);
                    $question.find('.question-description').val(existingData.description || '');
                    $question.find('.question-required').prop('checked', existingData.is_required);

                    // Update type badge
                    const typeLabels = {
                        'text': 'Short Text',
                        'textarea': 'Long Text',
                        'radio': 'Single Choice',
                        'checkbox': 'Multiple Choice',
                        'select': 'Dropdown',
                        'rating': 'Rating',
                        'scale': 'Scale',
                        'date': 'Date',
                        'file': 'File Upload'
                    };
                    $question.find('.question-type-badge').text(typeLabels[existingData.type] || existingData.type);

                    // Load options if applicable
                    if (['radio', 'checkbox', 'select'].includes(existingData.type)) {
                        const $optionsContainer = $question.find('.options-container');
                        $optionsContainer.show();

                        let options = [];
                        if (typeof existingData.options === 'string') {
                            try {
                                options = JSON.parse(existingData.options);
                            } catch (e) {
                                options = [];
                            }
                        } else if (Array.isArray(existingData.options)) {
                            options = existingData.options;
                        }

                        // Add existing options
                        options.forEach((option, idx) => {
                            addOption($question, option);
                        });
                    }
                }

                $('#questionsContainer').append($question);
                updateQuestionNumbers();
            }

            // Question Type Change
            $(document).on('change', '.question-type', function() {
                const $questionItem = $(this).closest('.question-item');
                const type = $(this).val();
                const $optionsContainer = $questionItem.find('.options-container');
                const $typeBadge = $questionItem.find('.question-type-badge');

                // Update type badge
                const typeLabels = {
                    'text': 'Short Text',
                    'textarea': 'Long Text',
                    'radio': 'Single Choice',
                    'checkbox': 'Multiple Choice',
                    'select': 'Dropdown',
                    'rating': 'Rating',
                    'scale': 'Scale',
                    'date': 'Date',
                    'file': 'File Upload'
                };
                $typeBadge.text(typeLabels[type] || type);

                // Show/hide options container
                if (['radio', 'checkbox', 'select'].includes(type)) {
                    $optionsContainer.show();
                    // Add default options if empty
                    if ($optionsContainer.find('.option-input').length === 0) {
                        addOption($questionItem);
                        addOption($questionItem);
                    }
                } else {
                    $optionsContainer.hide();
                }
            });

            // Add Option
            $(document).on('click', '.add-option', function() {
                const $questionItem = $(this).closest('.question-item');
                addOption($questionItem);
            });

            function addOption($questionItem, value = '') {
                const optionCount = $questionItem.find('.option-input').length + 1;
                const $optionHtml = $(`
                    <div class="input-group mb-2">
                        <input type="text" class="form-control form-control-sm option-input" 
                               placeholder="Option ${optionCount}" 
                               value="${value}" />
                        <button class="btn btn-outline-danger remove-option" type="button">
                            <i class="ri-close-line"></i>
                        </button>
                    </div>
                `);
                $questionItem.find('.options-list').append($optionHtml);
            }

            // Remove Option
            $(document).on('click', '.remove-option', function() {
                const $questionItem = $(this).closest('.question-item');
                $(this).closest('.input-group').remove();
                updateOptionPlaceholders($questionItem);
            });

            // Update option placeholders
            function updateOptionPlaceholders($questionItem) {
                $questionItem.find('.option-input').each(function(index) {
                    $(this).attr('placeholder', 'Option ' + (index + 1));
                });
            }

            // Move Question Up
            $(document).on('click', '.move-up', function() {
                const $question = $(this).closest('.question-item');
                const $prev = $question.prev('.question-item');
                if ($prev.length) {
                    $question.insertBefore($prev);
                    updateQuestionNumbers();
                }
            });

            // Move Question Down
            $(document).on('click', '.move-down', function() {
                const $question = $(this).closest('.question-item');
                const $next = $question.next('.question-item');
                if ($next.length) {
                    $question.insertAfter($next);
                    updateQuestionNumbers();
                }
            });

            // Remove Question
            $(document).on('click', '.remove-question', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This question will be removed!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).closest('.question-item').remove();
                        updateQuestionNumbers();

                        if ($('.question-item').length === 0) {
                            $('#noQuestionsMsg').show();
                        }
                    }
                });
            });

            // Update Question Numbers
            function updateQuestionNumbers() {
                $('.question-item').each(function(index) {
                    $(this).find('.question-number').text('Q' + (index + 1));
                });
            }

            // Form Submission
            $('#edit-questionnaire').on('submit', function(e) {
                e.preventDefault();

                // Validate at least one question
                if ($('.question-item').length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Questions',
                        text: 'Please add at least one question to the questionnaire.'
                    });
                    return false;
                }

                // Collect questions data
                const questions = [];
                $('.question-item').each(function() {
                    const $question = $(this);
                    const type = $question.find('.question-type').val();
                    const questionId = $question.find('.question-id').val();

                    const questionData = {
                        question: $question.find('.question-text').val(),
                        type: type,
                        description: $question.find('.question-description').val(),
                        is_required: $question.find('.question-required').is(':checked'),
                        options: []
                    };

                    // Add ID for existing questions
                    if (questionId) {
                        questionData.id = questionId;
                    }

                    // Collect options if applicable
                    if (['radio', 'checkbox', 'select'].includes(type)) {
                        $question.find('.option-input').each(function() {
                            const optionValue = $(this).val().trim();
                            if (optionValue) {
                                questionData.options.push(optionValue);
                            }
                        });

                        if (questionData.options.length === 0) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Missing Options',
                                text: 'Please add at least one option for question: ' +
                                    questionData.question
                            });
                            return false;
                        }
                    }

                    questions.push(questionData);
                });

                // Create FormData
                const formData = new FormData(this);
                formData.delete('questions[][question]');
                formData.delete('questions[][type]');
                formData.delete('questions[][description]');
                formData.delete('questions[][is_required]');
                formData.delete('questions[][options]');
                formData.delete('questions[][id]');
                formData.append('questions', JSON.stringify(questions));

                // Show loading
                Swal.fire({
                    title: 'Updating...',
                    text: 'Please wait while we update your questionnaire.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit via AJAX
                $.ajax({
                    url: '{{ route($url . '.update', $record->id) }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Questionnaire updated successfully.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = '{{ route($url . '.index') }}';
                        });
                    },
                    error: function(xhr) {
                        let errorMsg = 'Something went wrong. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: errorMsg
                        });
                    }
                });
            });
        });
    </script>
@stop
