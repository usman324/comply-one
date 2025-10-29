@extends('layout.master')

@section('css')
<style>
    .question-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .question-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .question-card.selected {
        border-color: var(--bs-primary);
        background-color: rgba(var(--bs-primary-rgb), 0.05);
    }
    
    .question-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    .workspace-count {
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    .sortable-ghost {
        opacity: 0.4;
        background: #f8f9fa;
    }
    
    .drag-handle {
        cursor: move;
        color: #adb5bd;
    }
    
    .drag-handle:hover {
        color: var(--bs-primary);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Assign Questions to Workspace</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:">Workspaces</a></li>
                        <li class="breadcrumb-item"><a href="javascript:">{{ $record->name }}</a></li>
                        <li class="breadcrumb-item active">Assign Questions</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Panel: Available Questions -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ri-list-check-2 me-2"></i>Available Questions
                        </h5>
                        <button type="button" class="btn btn-sm btn-primary" id="selectAllBtn">
                            <i class="ri-checkbox-multiple-line me-1"></i> Select All
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search & Filter -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <input type="text" class="form-control form-control-sm" id="searchQuestions" 
                                   placeholder="Search questions...">
                        </div>
                        <div class="col-md-4">
                            <select class="form-select form-select-sm" id="filterSection">
                                <option value="">All Questionnaire</option>
                                @foreach($questionnaires as $section)
                                <option value="{{ $section->id }}">{{ $section->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Questions List -->
                    <div id="availableQuestions" style="max-height: 600px; overflow-y: auto;">
                        @forelse($availableQuestions as $question)
                        <div class="question-card card mb-2" data-question-id="{{ $question->id }}" data-section-id="{{ $question->section_id }}">
                            <div class="card-body p-3">
                                <div class="form-check">
                                    <input class="form-check-input question-checkbox" type="checkbox" 
                                           value="{{ $question->id }}" id="question-{{ $question->id }}">
                                    <label class="form-check-label w-100" for="question-{{ $question->id }}">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $question->question }}</h6>
                                                @if($question->description)
                                                <p class="text-muted small mb-2">{{ Str::limit($question->description, 100) }}</p>
                                                @endif
                                                <div class="d-flex gap-2 flex-wrap">
                                                    <span class="badge question-badge bg-info-subtle text-info">
                                                        {{ ucfirst($question->type) }}
                                                    </span>
                                                    @if($question->is_required)
                                                    <span class="badge question-badge bg-danger-subtle text-danger">Required</span>
                                                    @endif
                                                    @if($question->section)
                                                    <span class="badge question-badge bg-primary-subtle text-primary">
                                                        {{ $question->section->name }}
                                                    </span>
                                                    @endif
                                                    <span class="workspace-count">
                                                        <i class="ri-building-line"></i> Used in {{ $question->getWorkspaceCount() }} workspaces
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-5">
                            <i class="ri-questionnaire-line" style="font-size: 3rem;"></i>
                            <p class="mt-2">No questions available</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Selected Questions -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ri-file-list-3-line me-2"></i>Selected Questions
                            <span class="badge bg-primary ms-2" id="selectedCount">0</span>
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-danger" id="clearAllBtn">
                            <i class="ri-delete-bin-line me-1"></i> Clear All
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="ri-information-line me-2"></i>
                        <strong>Workspace:</strong> {{ $record->name }}<br>
                        <small>Drag to reorder, click settings to customize per question</small>
                    </div>

                    <!-- Selected Questions List (Sortable) -->
                    <div id="selectedQuestions" style="max-height: 600px; overflow-y: auto;">
                        <div class="text-center text-muted py-5" id="noSelectionMsg">
                            <i class="ri-file-list-line" style="font-size: 3rem;"></i>
                            <p class="mt-2">No questions selected yet</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-light" onclick="window.history.back()">
                                <i class="ri-arrow-left-line me-1"></i> Cancel
                            </button>
                            <button type="button" class="btn btn-primary" id="saveAssignmentBtn">
                                <i class="ri-save-line me-1"></i> Save Assignment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Question Settings Modal -->
<div class="modal fade" id="questionSettingsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Question Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="settingsQuestionId">
                <div class="mb-3">
                    <h6 id="settingsQuestionText"></h6>
                </div>
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="settingsRequired">
                        <label class="form-check-label" for="settingsRequired">
                            Required for this workspace
                        </label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Order</label>
                    <input type="number" class="form-control" id="settingsOrder" min="0">
                    <small class="text-muted">Leave blank for automatic ordering</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveSettingsBtn">Save Settings</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- Sortable.js for drag & drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
$(document).ready(function() {
    const workspaceId = {{ $record->id }};
    let selectedQuestions = [];
    let currentlyAssignedQuestions = @json($record->questions->pluck('id')->toArray());

    // Initialize Sortable on selected questions list
    const sortable = new Sortable(document.getElementById('selectedQuestions'), {
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'sortable-ghost',
        onEnd: function() {
            updateSelectedQuestions();
        }
    });

    // Search functionality
    $('#searchQuestions').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        filterQuestions();
    });

    // Filter by section
    $('#filterSection').on('change', function() {
        filterQuestions();
    });

    function filterQuestions() {
        const searchTerm = $('#searchQuestions').val().toLowerCase();
        const sectionId = $('#filterSection').val();

        $('.question-card').each(function() {
            const $card = $(this);
            const questionText = $card.find('h6').text().toLowerCase();
            const cardSectionId = $card.data('section-id');
            
            let showCard = true;
            
            if (searchTerm && !questionText.includes(searchTerm)) {
                showCard = false;
            }
            
            if (sectionId && cardSectionId != sectionId) {
                showCard = false;
            }
            
            $card.toggle(showCard);
        });
    }

    // Select/Deselect question
    $(document).on('change', '.question-checkbox', function() {
        const questionId = $(this).val();
        const $card = $(this).closest('.question-card');
        
        if ($(this).is(':checked')) {
            $card.addClass('selected');
            addToSelected(questionId, $card);
        } else {
            $card.removeClass('selected');
            removeFromSelected(questionId);
        }
        
        updateSelectedCount();
    });

    // Select All
    $('#selectAllBtn').on('click', function() {
        $('.question-checkbox:visible').prop('checked', true).trigger('change');
    });

    // Clear All
    $('#clearAllBtn').on('click', function() {
        if (selectedQuestions.length === 0) return;
        
        Swal.fire({
            title: 'Clear all?',
            text: 'This will remove all selected questions',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, clear all'
        }).then((result) => {
            if (result.isConfirmed) {
                $('.question-checkbox').prop('checked', false);
                $('.question-card').removeClass('selected');
                selectedQuestions = [];
                $('#selectedQuestions').html($('#noSelectionMsg').clone().show());
                updateSelectedCount();
            }
        });
    });

    function addToSelected(questionId, $card) {
        const questionData = {
            id: questionId,
            text: $card.find('h6').text(),
            type: $card.find('.badge.bg-info-subtle').text().trim(),
            is_required: $card.find('.badge.bg-danger-subtle').length > 0,
            order: selectedQuestions.length
        };
        
        selectedQuestions.push(questionData);
        renderSelectedQuestions();
    }

    function removeFromSelected(questionId) {
        selectedQuestions = selectedQuestions.filter(q => q.id != questionId);
        renderSelectedQuestions();
    }

    function renderSelectedQuestions() {
        const $container = $('#selectedQuestions');
        $container.empty();
        
        if (selectedQuestions.length === 0) {
            $container.html($('#noSelectionMsg').clone().show());
            return;
        }
        
        selectedQuestions.forEach((question, index) => {
            const html = `
                <div class="card mb-2" data-question-id="${question.id}">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start gap-2">
                            <div class="drag-handle">
                                <i class="ri-draggable fs-5"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <span class="badge bg-light text-dark me-2">${index + 1}</span>
                                        <strong>${question.text}</strong>
                                    </div>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-light settings-btn" data-question-id="${question.id}">
                                            <i class="ri-settings-4-line"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light remove-btn" data-question-id="${question.id}">
                                            <i class="ri-close-line"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-info-subtle text-info">${question.type}</span>
                                    ${question.is_required ? '<span class="badge bg-danger-subtle text-danger">Required</span>' : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $container.append(html);
        });
    }

    function updateSelectedQuestions() {
        const newOrder = [];
        $('#selectedQuestions .card').each(function(index) {
            const questionId = $(this).data('question-id');
            const question = selectedQuestions.find(q => q.id == questionId);
            if (question) {
                question.order = index;
                newOrder.push(question);
            }
        });
        selectedQuestions = newOrder;
        renderSelectedQuestions();
    }

    function updateSelectedCount() {
        $('#selectedCount').text(selectedQuestions.length);
    }

    // Remove from selected
    $(document).on('click', '.remove-btn', function() {
        const questionId = $(this).data('question-id');
        $(`#question-${questionId}`).prop('checked', false);
        $(`.question-card[data-question-id="${questionId}"]`).removeClass('selected');
        removeFromSelected(questionId);
        updateSelectedCount();
    });

    // Open settings modal
    $(document).on('click', '.settings-btn', function() {
        const questionId = $(this).data('question-id');
        const question = selectedQuestions.find(q => q.id == questionId);
        
        $('#settingsQuestionId').val(questionId);
        $('#settingsQuestionText').text(question.text);
        $('#settingsRequired').prop('checked', question.is_required);
        $('#settingsOrder').val(question.order);
        
        new bootstrap.Modal('#questionSettingsModal').show();
    });

    // Save settings
    $('#saveSettingsBtn').on('click', function() {
        const questionId = $('#settingsQuestionId').val();
        const question = selectedQuestions.find(q => q.id == questionId);
        
        if (question) {
            question.is_required = $('#settingsRequired').is(':checked');
            question.order = parseInt($('#settingsOrder').val()) || question.order;
            renderSelectedQuestions();
        }
        
        bootstrap.Modal.getInstance('#questionSettingsModal').hide();
    });

    // Save assignment
    $('#saveAssignmentBtn').on('click', function() {
        if (selectedQuestions.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Questions Selected',
                text: 'Please select at least one question to assign'
            });
            return;
        }

        const questionsData = selectedQuestions.map(q => ({
            id: q.id,
            order: q.order,
            is_required: q.is_required
        }));

        Swal.fire({
            title: 'Saving...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: `/workspaces/${workspaceId}/assign-questions`,
            method: 'POST',
            data: {
                questions: questionsData,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    timer: 2000
                }).then(() => {
                    window.location.href = `/workspaces/${workspaceId}`;
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to assign questions'
                });
            }
        });
    });

    // Pre-select already assigned questions
    currentlyAssignedQuestions.forEach(questionId => {
        $(`#question-${questionId}`).prop('checked', true).trigger('change');
    });
});
</script>
@endsection