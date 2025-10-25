<div class="modal fade" id="addModel" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title">Create {{ $title }}</h4>
                <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="questionnaire-form" id="add-questionnaire">
                    <!-- Basic Information -->
                    <div class="card mb-3">
                        <div class="card-header bg-soft-primary">
                            <h6 class="mb-0">Basic Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="title"
                                        placeholder="e.g., Customer Satisfaction Survey" required />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="customer_feedback">Customer Feedback</option>
                                        <option value="employee_survey">Employee Survey</option>
                                        <option value="market_research">Market Research</option>
                                        <option value="event_feedback">Event Feedback</option>
                                        <option value="product_feedback">Product Feedback</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3"
                                        placeholder="Brief description of what this questionnaire is about..."></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" class="form-control" name="end_date" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Questions Builder -->
                    <div class="card mb-3">
                        <div class="card-header bg-soft-primary d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Questions</h6>
                            <button type="button" class="btn btn-sm btn-primary" id="addQuestionBtn">
                                <i class="ri-add-line me-1"></i> Add Question
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="questionsContainer" class="questions-list">
                                <!-- Questions will be added here dynamically -->
                                <div class="text-center text-muted py-4" id="noQuestionsMsg">
                                    <i class="ri-questionnaire-line" style="font-size: 3rem;"></i>
                                    <p class="mt-2">No questions added yet. Click "Add Question" to get started.</p>
                                </div>
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
                                            name="allow_anonymous" checked>
                                        <label class="form-check-label" for="allowAnonymous">
                                            Allow Anonymous Responses
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="multipleResponses"
                                            name="allow_multiple_responses">
                                        <label class="form-check-label" for="multipleResponses">
                                            Allow Multiple Responses
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="showProgress"
                                            name="show_progress" checked>
                                        <label class="form-check-label" for="showProgress">
                                            Show Progress Bar
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="randomizeQuestions"
                                            name="randomize_questions">
                                        <label class="form-check-label" for="randomizeQuestions">
                                            Randomize Question Order
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i> Create Questionnaire
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Question Template -->
<template id="questionTemplate">
    <div class="question-item card mb-3" data-question-index="">
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
                    <input type="text" class="form-control question-text" name="questions[][question]"
                        placeholder="Enter your question here..." required />
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Question Type <span class="text-danger">*</span></label>
                    <select class="form-control question-type" name="questions[][type]" required>
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
                    <input type="text" class="form-control question-description" name="questions[][description]"
                        placeholder="Add helper text or instructions..." />
                </div>

                <!-- Options Container (for radio, checkbox, select) -->
                <div class="col-md-12 mb-3 options-container" style="display: none;">
                    <label class="form-label">Options <span class="text-danger">*</span></label>
                    <div class="options-list">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control option-input" placeholder="Option 1" />
                            <button class="btn btn-outline-danger remove-option" type="button">
                                <i class="ri-close-line"></i>
                            </button>
                        </div>
                    </div>
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

<script>
    $(document).ready(function() {
        let questionIndex = 0;

        // Initialize Select2
        $('.select2').select2({
            dropdownParent: $('#addModel')
        });

        // Add Question Button
        $('#addQuestionBtn').on('click', function() {
            addQuestion();
        });

        // Add Question Function
        function addQuestion() {
            const template = $('#questionTemplate').html();
            const $question = $(template);

            questionIndex++;
            $question.attr('data-question-index', questionIndex);
            $question.find('.question-number').text('Q' + questionIndex);

            $('#noQuestionsMsg').hide();
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

        function addOption($questionItem) {
            const optionCount = $questionItem.find('.option-input').length + 1;
            const $optionHtml = $(`
                <div class="input-group mb-2">
                    <input type="text" class="form-control option-input" placeholder="Option ${optionCount}" />
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
        $('#add-questionnaire').on('submit', function(e) {
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
                const questionData = {
                    question: $question.find('.question-text').val(),
                    type: type,
                    description: $question.find('.question-description').val(),
                    is_required: $question.find('.question-required').is(':checked'),
                    options: []
                };

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
                                questionData
                                .question
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
            formData.append('questions', JSON.stringify(questions));

            // Show loading
            Swal.fire({
                title: 'Creating...',
                text: 'Please wait while we create your questionnaire.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit via AJAX
            $.ajax({
                url: '{{ $url }}',
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
                        text: 'Questionnaire created successfully.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        $('#addModel').modal('hide');
                        myTable.ajax.reload();
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
