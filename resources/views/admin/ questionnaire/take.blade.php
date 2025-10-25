@extends('layout.master')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Progress Header -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0">{{ $questionnaire->title }}</h4>
                            <span class="badge bg-primary">Question <span id="currentQuestion">1</span> of <span
                                    id="totalQuestions">{{ count($questions) }}</span></span>
                        </div>

                        <!-- Progress Bar -->
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressBar"
                                role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>

                        <!-- Time Estimate -->
                        <div class="text-muted small mt-2">
                            <i class="ri-time-line me-1"></i>
                            Estimated time: <span id="timeEstimate">{{ count($questions) * 0.5 }} minutes</span>
                        </div>
                    </div>
                </div>

                <!-- Question Card -->
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form id="questionnaireForm">
                            @csrf
                            <input type="hidden" name="questionnaire_id" value="{{ $questionnaire->id }}">

                            <!-- Questions Container -->
                            <div id="questionsContainer">
                                @foreach ($questions as $index => $question)
                                    <div class="question-slide" data-question-id="{{ $question->id }}"
                                        data-question-index="{{ $index }}"
                                        style="display: {{ $index === 0 ? 'block' : 'none' }}">

                                        <!-- Question Number & Text -->
                                        <div class="mb-4">
                                            <span class="badge bg-soft-primary text-primary mb-2">Question
                                                {{ $index + 1 }}</span>
                                            <h5 class="question-text fw-semibold">
                                                {{ $question->question }}
                                                @if ($question->is_required)
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </h5>
                                            @if ($question->description)
                                                <p class="text-muted small">{{ $question->description }}</p>
                                            @endif
                                        </div>

                                        <!-- Answer Input Based on Type -->
                                        <div class="answer-container">
                                            @switch($question->type)
                                                @case('text')
                                                    <input type="text" class="form-control form-control-lg answer-input"
                                                        name="answers[{{ $question->id }}]"
                                                        placeholder="Type your answer here..."
                                                        {{ $question->is_required ? 'required' : '' }}>
                                                @break

                                                @case('textarea')
                                                    <textarea class="form-control form-control-lg answer-input" name="answers[{{ $question->id }}]" rows="5"
                                                        placeholder="Type your detailed answer here..." {{ $question->is_required ? 'required' : '' }}></textarea>
                                                @break

                                                @case('radio')
                                                    <div class="radio-options">
                                                        @foreach ($question->options as $option)
                                                            <div class="form-check form-check-lg mb-3 p-3 border rounded hover-effect">
                                                                <input class="form-check-input answer-input" type="radio"
                                                                    name="answers[{{ $question->id }}]"
                                                                    id="option_{{ $question->id }}_{{ $loop->index }}"
                                                                    value="{{ $option }}"
                                                                    {{ $question->is_required ? 'required' : '' }}>
                                                                <label class="form-check-label w-100 cursor-pointer"
                                                                    for="option_{{ $question->id }}_{{ $loop->index }}">
                                                                    {{ $option }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @break

                                                @case('checkbox')
                                                    <div class="checkbox-options">
                                                        @foreach ($question->options as $option)
                                                            <div class="form-check form-check-lg mb-3 p-3 border rounded hover-effect">
                                                                <input class="form-check-input answer-input" type="checkbox"
                                                                    name="answers[{{ $question->id }}][]"
                                                                    id="checkbox_{{ $question->id }}_{{ $loop->index }}"
                                                                    value="{{ $option }}">
                                                                <label class="form-check-label w-100 cursor-pointer"
                                                                    for="checkbox_{{ $question->id }}_{{ $loop->index }}">
                                                                    {{ $option }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @break

                                                @case('select')
                                                    <select class="form-select form-select-lg answer-input"
                                                        name="answers[{{ $question->id }}]"
                                                        {{ $question->is_required ? 'required' : '' }}>
                                                        <option value="">Select an option...</option>
                                                        @foreach ($question->options as $option)
                                                            <option value="{{ $option }}">{{ $option }}</option>
                                                        @endforeach
                                                    </select>
                                                @break

                                                @case('rating')
                                                    <div class="rating-container text-center py-3">
                                                        <div class="rating-stars" data-question-id="{{ $question->id }}">
                                                            @for ($i = 1; $i <= ($question->max_rating ?? 5); $i++)
                                                                <i class="ri-star-line rating-star" data-rating="{{ $i }}"
                                                                    style="font-size: 2.5rem; cursor: pointer; color: #ddd; margin: 0 5px;"></i>
                                                            @endfor
                                                        </div>
                                                        <input type="hidden" class="answer-input"
                                                            name="answers[{{ $question->id }}]"
                                                            {{ $question->is_required ? 'required' : '' }}>
                                                        <div class="rating-label text-muted mt-2"></div>
                                                    </div>
                                                @break

                                                @case('scale')
                                                    <div class="scale-container text-center py-3">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <span class="text-muted small">{{ $question->scale_min_label ?? '1' }}</span>
                                                            <span
                                                                class="text-muted small">{{ $question->scale_max_label ?? '10' }}</span>
                                                        </div>
                                                        <input type="range" class="form-range answer-input scale-input"
                                                            name="answers[{{ $question->id }}]" min="1" max="10"
                                                            step="1" value="5"
                                                            {{ $question->is_required ? 'required' : '' }}>
                                                        <div class="scale-value-display text-primary fw-bold mt-2"
                                                            style="font-size: 1.5rem;">5</div>
                                                    </div>
                                                @break

                                                @case('date')
                                                    <input type="date" class="form-control form-control-lg answer-input"
                                                        name="answers[{{ $question->id }}]"
                                                        {{ $question->is_required ? 'required' : '' }}>
                                                @break

                                                @case('file')
                                                    <input type="file" class="form-control form-control-lg answer-input"
                                                        name="answers[{{ $question->id }}]"
                                                        accept="{{ $question->accepted_files ?? '*' }}"
                                                        {{ $question->is_required ? 'required' : '' }}>
                                                @break
                                            @endswitch
                                        </div>

                                        <!-- Skip Option -->
                                        @if (!$question->is_required)
                                            <div class="text-center mt-3">
                                                <button type="button" class="btn btn-link btn-sm text-muted skip-question">
                                                    Skip this question
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                <button type="button" class="btn btn-outline-secondary" id="prevBtn"
                                    style="display: none;">
                                    <i class="ri-arrow-left-line me-1"></i> Previous
                                </button>

                                <div class="ms-auto">
                                    <button type="button" class="btn btn-primary" id="nextBtn">
                                        Next <i class="ri-arrow-right-line ms-1"></i>
                                    </button>
                                    <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                                        <i class="ri-check-line me-1"></i> Submit Answers
                                    </button>
                                </div>
                            </div>

                            <!-- Save & Continue Later -->
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-link btn-sm text-muted" id="saveDraftBtn">
                                    <i class="ri-save-line me-1"></i> Save & Continue Later
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Motivational Tips -->
                <div class="text-center mt-3 text-muted small">
                    <i class="ri-lightbulb-line me-1"></i>
                    <span id="motivationalTip">You're doing great! Keep going.</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            let currentQuestion = 0;
            const totalQuestions = {{ count($questions) }};
            const questions = $('.question-slide');
            const startTime = new Date();

            // Motivational tips that rotate
            const motivationalTips = [
                "You're doing great! Keep going.",
                "Almost there! Your input is valuable.",
                "Great progress! Just a few more questions.",
                "Excellent! Your answers help us improve.",
                "You're halfway through! Keep it up!",
                "Fantastic! We appreciate your time.",
                "Well done! Nearly finished."
            ];

            // Initialize
            updateProgress();
            updateNavigation();

            // Rating stars functionality
            $('.rating-star').on('click', function() {
                const rating = $(this).data('rating');
                const container = $(this).closest('.rating-stars');
                const questionId = container.data('question-id');
                const input = container.siblings('input[type="hidden"]');

                input.val(rating);

                // Update star display
                container.find('.rating-star').each(function() {
                    const starRating = $(this).data('rating');
                    if (starRating <= rating) {
                        $(this).removeClass('ri-star-line').addClass('ri-star-fill').css('color',
                            '#FFD700');
                    } else {
                        $(this).removeClass('ri-star-fill').addClass('ri-star-line').css('color',
                            '#ddd');
                    }
                });

                // Update label
                const labels = ['Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
                container.siblings('.rating-label').text(labels[rating - 1] || '');
            });

            // Hover effect for rating stars
            $('.rating-star').on('mouseenter', function() {
                const rating = $(this).data('rating');
                const container = $(this).closest('.rating-stars');

                container.find('.rating-star').each(function() {
                    const starRating = $(this).data('rating');
                    if (starRating <= rating) {
                        $(this).css('color', '#FFA500');
                    } else {
                        $(this).css('color', '#ddd');
                    }
                });
            });

            $('.rating-stars').on('mouseleave', function() {
                const input = $(this).siblings('input[type="hidden"]');
                const savedRating = input.val();
                const container = $(this);

                container.find('.rating-star').each(function() {
                    const starRating = $(this).data('rating');
                    if (savedRating && starRating <= savedRating) {
                        $(this).css('color', '#FFD700');
                    } else {
                        $(this).css('color', '#ddd');
                    }
                });
            });

            // Scale slider functionality
            $('.scale-input').on('input', function() {
                const value = $(this).val();
                $(this).siblings('.scale-value-display').text(value);
            });

            // Auto-advance for radio buttons
            $('input[type="radio"]').on('change', function() {
                setTimeout(() => {
                    if (currentQuestion < totalQuestions - 1) {
                        nextQuestion();
                    }
                }, 300);
            });

            // Next button
            $('#nextBtn').on('click', function() {
                if (validateCurrentQuestion()) {
                    nextQuestion();
                }
            });

            // Previous button
            $('#prevBtn').on('click', function() {
                previousQuestion();
            });

            // Skip button
            $('.skip-question').on('click', function() {
                // Clear current question answer
                $(this).closest('.question-slide').find('.answer-input').val('').prop('checked', false);
                nextQuestion();
            });

            // Submit form
            $('#questionnaireForm').on('submit', function(e) {
                e.preventDefault();

                if (!validateCurrentQuestion()) {
                    return false;
                }

                const formData = new FormData(this);
                const timeSpent = Math.round((new Date() - startTime) / 1000); // in seconds
                formData.append('time_spent', timeSpent);

                // Show loading
                Swal.fire({
                    title: 'Submitting...',
                    text: 'Please wait while we save your responses.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '{{ $url }}/submit',
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
                            title: 'Thank You!',
                            text: 'Your responses have been submitted successfully.',
                            confirmButtonText: 'Done'
                        }).then(() => {
                            window.location.href = '{{ url()->previous() }}';
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong. Please try again.'
                        });
                    }
                });
            });

            // Save draft
            $('#saveDraftBtn').on('click', function() {
                const formData = new FormData($('#questionnaireForm')[0]);
                formData.append('is_draft', 1);
                formData.append('current_question', currentQuestion);

                $.ajax({
                    url: '{{ $url }}/save-draft',
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
                            title: 'Draft Saved!',
                            text: 'You can continue later from where you left off.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });
            });

            function nextQuestion() {
                if (currentQuestion < totalQuestions - 1) {
                    // Fade out current
                    $(questions[currentQuestion]).fadeOut(200, function() {
                        currentQuestion++;
                        // Fade in next
                        $(questions[currentQuestion]).fadeIn(200);
                        updateProgress();
                        updateNavigation();
                        updateMotivationalTip();
                        // Scroll to top
                        $('html, body').animate({
                            scrollTop: $('.card').offset().top - 100
                        }, 300);
                    });
                }
            }

            function previousQuestion() {
                if (currentQuestion > 0) {
                    $(questions[currentQuestion]).fadeOut(200, function() {
                        currentQuestion--;
                        $(questions[currentQuestion]).fadeIn(200);
                        updateProgress();
                        updateNavigation();
                        $('html, body').animate({
                            scrollTop: $('.card').offset().top - 100
                        }, 300);
                    });
                }
            }

            function updateProgress() {
                const progress = ((currentQuestion + 1) / totalQuestions) * 100;
                $('#progressBar').css('width', progress + '%').attr('aria-valuenow', progress);
                $('#currentQuestion').text(currentQuestion + 1);

                // Update time estimate
                const remainingQuestions = totalQuestions - (currentQuestion + 1);
                const estimatedMinutes = Math.max(1, Math.round(remainingQuestions * 0.5));
                $('#timeEstimate').text(estimatedMinutes + ' minute' + (estimatedMinutes > 1 ? 's' : ''));
            }

            function updateNavigation() {
                // Show/hide previous button
                if (currentQuestion === 0) {
                    $('#prevBtn').hide();
                } else {
                    $('#prevBtn').show();
                }

                // Show/hide next and submit buttons
                if (currentQuestion === totalQuestions - 1) {
                    $('#nextBtn').hide();
                    $('#submitBtn').show();
                } else {
                    $('#nextBtn').show();
                    $('#submitBtn').hide();
                }
            }

            function updateMotivationalTip() {
                const milestones = [0, Math.floor(totalQuestions * 0.25), Math.floor(totalQuestions * 0.5),
                    Math.floor(totalQuestions * 0.75), totalQuestions - 1
                ];
                const milestoneIndex = milestones.indexOf(currentQuestion);

                if (milestoneIndex !== -1) {
                    $('#motivationalTip').fadeOut(200, function() {
                        $(this).text(motivationalTips[milestoneIndex]).fadeIn(200);
                    });
                }
            }

            function validateCurrentQuestion() {
                const currentSlide = $(questions[currentQuestion]);
                const requiredInputs = currentSlide.find('.answer-input[required]');
                let isValid = true;

                requiredInputs.each(function() {
                    const input = $(this);
                    const type = input.attr('type');

                    if (type === 'checkbox') {
                        const checkboxGroup = currentSlide.find(
                            `input[name="${input.attr('name')}"]`);
                        if (checkboxGroup.filter(':checked').length === 0) {
                            isValid = false;
                        }
                    } else if (type === 'radio') {
                        const radioGroup = currentSlide.find(`input[name="${input.attr('name')}"]`);
                        if (radioGroup.filter(':checked').length === 0) {
                            isValid = false;
                        }
                    } else if (!input.val()) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Required Field',
                        text: 'Please answer this question before continuing.',
                        confirmButtonText: 'OK'
                    });
                }

                return isValid;
            }

            // Keyboard navigation
            $(document).on('keydown', function(e) {
                if (e.key === 'Enter' && !$(e.target).is('textarea')) {
                    e.preventDefault();
                    if (currentQuestion < totalQuestions - 1) {
                        $('#nextBtn').click();
                    }
                }
            });

            // Auto-save progress every 30 seconds
            setInterval(function() {
                if (currentQuestion > 0) {
                    $('#saveDraftBtn').click();
                }
            }, 30000);
        });
    </script>

    <style>
        .question-slide {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hover-effect {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .hover-effect:hover {
            background-color: #f8f9fa;
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
        }

        .form-check-input:checked~.form-check-label {
            font-weight: 500;
            color: #0d6efd;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .rating-star {
            transition: all 0.2s ease;
        }

        .rating-star:hover {
            transform: scale(1.2);
        }

        .scale-input {
            cursor: pointer;
        }

        .progress-bar {
            transition: width 0.5s ease;
        }

        .badge.bg-soft-primary {
            background-color: rgba(13, 110, 253, 0.1);
        }
    </style>
@endsection
