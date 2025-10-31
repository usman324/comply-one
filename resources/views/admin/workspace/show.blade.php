@extends('layout.master')
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Workspace Details</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ $url }}">Workspaces</a></li>
                            <li class="breadcrumb-item active">{{ $record->name }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Workspace Overview Card -->
        <div class="row">
            <div class="col-xxl-3">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mx-auto avatar-xl mb-3">
                            <img src="{{ asset('dummy.jpeg') }}" alt="{{ $record->name }}"
                                class="img-thumbnail rounded-circle"
                                style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                        <h5 class="mb-1">{{ $record->name }}</h5>
                        <p class="text-muted mb-3">{{ $record->workspace_number }}</p>

                        <div class="d-flex justify-content-center gap-2 mb-3">
                            {!! $record->getTypeBadge() !!}
                            {!! $record->getStatusBadge() !!}
                        </div>

                        @if ($record->description)
                            <div class="text-start">
                                <p class="text-muted mb-0"><small>{{ $record->description }}</small></p>
                            </div>
                        @endif
                    </div>
                    <div class="card-body border-top">
                        <div class="d-flex gap-2 mb-2">
                            @can('edit_' . $permission)
                                <a href="{{ $url . '/' . $record->id . '/users' }}" class="btn btn-primary w-100">
                                    <i class="ri-group-line align-bottom me-1"></i> Manage Users
                                </a>
                            @endcan
                            @can('edit_' . $permission)
                            {{-- {{ url('/file-managers') }} --}}
                                <a href="{{ $url . '/' . $record->id . '/edit' }}" class="btn btn-primary w-100">
                                    <i class="ri-folder-add-fill align-bottom me-1"></i> Manage Files
                                </a>
                            @endcan

                        </div>

                        <div class="d-flex gap-2">
                            @can('edit_' . $permission)
                                <a href="{{ $url . '/' . $record->id . '/edit' }}" class="btn btn-primary w-100">
                                    <i class="ri-edit-line align-bottom me-1"></i> Edit Workspace
                                </a>
                            @endcan
                            {{-- <button type="button" class="btn btn-soft-danger"
                                onclick="deleteWorkspace({{ $record->id }})">
                                <i class="ri-delete-bin-line"></i>
                            </button> --}}
                        </div>
                    </div>
                </div>

                <!-- Workspace Info Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ri-information-line align-bottom me-1"></i> Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th class="ps-0" scope="row">Workspace ID:</th>
                                        <td class="text-muted">{{ $record->workspace_number }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Type:</th>
                                        <td class="text-muted">{!! $record->getTypeBadge() !!}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Status:</th>
                                        <td class="text-muted">{!! $record->getStatusBadge() !!}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Owner:</th>
                                        <td class="text-muted">
                                            @if ($record->owner)
                                                {{ $record->owner->first_name }} {{ $record->owner->last_name }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Created:</th>
                                        <td class="text-muted">{{ $record->created_at->format('M d, Y h:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Last Updated:</th>
                                        <td class="text-muted">{{ $record->updated_at->format('M d, Y h:i A') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questionnaire Responses -->
            <div class="col-xxl-9">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs nav-border-top nav-border-top-primary mb-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#overview" role="tab">
                                    <i class="ri-dashboard-line align-bottom me-1"></i> Overview
                                </a>
                            </li>
                            @foreach ($questionnaireSections as $index => $section)
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#section-{{ $section['slug'] }}"
                                        role="tab">
                                        <i class="ri-questionnaire-line align-bottom me-1"></i> {{ $section['name'] }}
                                    </a>
                                </li>
                            @endforeach
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#activity" role="tab">
                                    <i class="ri-time-line align-bottom me-1"></i> Activity
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Overview Tab -->
                            <div class="tab-pane active" id="overview" role="tabpanel">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="mb-3">Workspace Summary</h5>
                                        <div class="alert alert-info">
                                            <i class="ri-information-line align-middle me-2"></i>
                                            This workspace contains
                                            <strong>{{ $record->questionnaireResponses->count() }}</strong> questionnaire
                                            responses across <strong>{{ $questionnaireSections->count() }}</strong>
                                            sections.
                                        </div>
                                    </div>

                                    <!-- Quick Stats -->
                                    <div class="col-xl-4 col-md-6">
                                        <div class="card card-animate">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <p class="text-uppercase fw-medium text-muted mb-0">Total Responses
                                                        </p>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <h5 class="text-success fs-14 mb-0">
                                                            <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                                        </h5>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-end justify-content-between mt-2">
                                                    <div>
                                                        <h4 class="fs-22 fw-semibold ff-secondary mb-2">
                                                            {{ $record->questionnaireResponses->count() }}
                                                        </h4>
                                                    </div>
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-success-subtle rounded fs-3">
                                                            <i class="ri-checkbox-circle-line text-success"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-md-6">
                                        <div class="card card-animate">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <p class="text-uppercase fw-medium text-muted mb-0">Sections
                                                            Completed</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-end justify-content-between mt-2">
                                                    <div>
                                                        <h4 class="fs-22 fw-semibold ff-secondary mb-2">
                                                            {{ $questionnaireSections->count() }}
                                                        </h4>
                                                    </div>
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-info-subtle rounded fs-3">
                                                            <i class="ri-file-list-3-line text-info"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-md-6">
                                        <div class="card card-animate">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <p class="text-uppercase fw-medium text-muted mb-0">Last Updated
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-end justify-content-between mt-2">
                                                    <div>
                                                        <h4 class="fs-22 fw-semibold ff-secondary mb-2">
                                                            <small
                                                                class="fs-14">{{ $record->updated_at->diffForHumans() }}</small>
                                                        </h4>
                                                    </div>
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-warning-subtle rounded fs-3">
                                                            <i class="ri-time-line text-warning"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section Progress -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h5 class="mb-3">Section Completion</h5>
                                        @foreach ($questionnaireSections as $section)
                                            @php
                                                $sectionQuestionIds = $section['questions']->pluck('id')->toArray();
                                                $sectionResponses = $record->questionnaireResponses->whereIn(
                                                    'question_id',
                                                    $sectionQuestionIds,
                                                );
                                                $totalQuestions = count($sectionQuestionIds);
                                                $answeredQuestions = $sectionResponses->count();
                                                $percentage =
                                                    $totalQuestions > 0
                                                        ? round(($answeredQuestions / $totalQuestions) * 100)
                                                        : 0;
                                            @endphp
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="fw-medium">{{ $section['name'] }}</span>
                                                    <span
                                                        class="text-muted">{{ $answeredQuestions }}/{{ $totalQuestions }}
                                                        questions</span>
                                                </div>
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar {{ $percentage == 100 ? 'bg-success' : 'bg-primary' }}"
                                                        role="progressbar" style="width: {{ $percentage }}%"
                                                        aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Dynamic Questionnaire Section Tabs -->
                            @foreach ($questionnaireSections as $section)
                                <div class="tab-pane" id="section-{{ $section['slug'] }}" role="tabpanel">
                                    <h5 class="mb-3">{{ $section['name'] }}</h5>
                                    @if ($section['description'])
                                        <p class="text-muted">{{ $section['description'] }}</p>
                                    @endif

                                    <div class="accordion" id="accordion-{{ $section['slug'] }}">
                                        @foreach ($section['questions'] as $qIndex => $question)
                                            @php
                                                $response = $existingResponses[$question->id] ?? null;
                                            @endphp
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="heading-{{ $question->id }}">
                                                    <button class="accordion-button {{ $qIndex == 0 ? '' : 'collapsed' }}"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapse-{{ $question->id }}"
                                                        aria-expanded="{{ $qIndex == 0 ? 'true' : 'false' }}"
                                                        aria-controls="collapse-{{ $question->id }}">
                                                        <div class="d-flex align-items-center w-100">
                                                            <div class="flex-grow-1">
                                                                <span class="fw-semibold">{{ $question->question }}</span>
                                                                @if ($question->is_required)
                                                                    <span
                                                                        class="badge bg-danger-subtle text-danger ms-2">Required</span>
                                                                @endif
                                                            </div>
                                                            @if ($response)
                                                                <span class="badge bg-success-subtle text-success me-2">
                                                                    <i class="ri-checkbox-circle-line"></i> Answered
                                                                </span>
                                                            @else
                                                                <span class="badge bg-warning-subtle text-warning me-2">
                                                                    <i class="ri-error-warning-line"></i> No Response
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse-{{ $question->id }}"
                                                    class="accordion-collapse collapse {{ $qIndex == 0 ? 'show' : '' }}"
                                                    aria-labelledby="heading-{{ $question->id }}"
                                                    data-bs-parent="#accordion-{{ $section['slug'] }}">
                                                    <div class="accordion-body">
                                                        @if ($question->description)
                                                            <p class="text-muted small mb-3"><i
                                                                    class="ri-information-line me-1"></i>{{ $question->description }}
                                                            </p>
                                                        @endif

                                                        <div class="card bg-light border-0">
                                                            <div class="card-body">
                                                                <p class="mb-1"><strong>Question Type:</strong> <span
                                                                        class="badge bg-info-subtle text-info">{{ ucfirst($question->type) }}</span>
                                                                </p>

                                                                <p class="mb-2"><strong>Response:</strong></p>
                                                                @if ($response)
                                                                    @if (is_array($response))
                                                                        <ul class="mb-0">
                                                                            @foreach ($response as $item)
                                                                                <li>{{ $item }}</li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @else
                                                                        <p class="mb-0 fs-15 text-dark">
                                                                            {{ $response }}</p>
                                                                    @endif
                                                                @else
                                                                    <p class="mb-0 text-muted"><em>No response
                                                                            provided</em></p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            <!-- Activity Tab -->
                            <div class="tab-pane" id="activity" role="tabpanel">
                                <h5 class="mb-3">Recent Activity</h5>
                                <div class="profile-timeline">
                                    <div class="accordion accordion-flush" id="activityTimeline">
                                        <!-- Created Event -->
                                        <div class="accordion-item border-0">
                                            <div class="accordion-header" id="headingOne">
                                                <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse"
                                                    href="#collapseOne" aria-expanded="true">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 avatar-xs">
                                                            <div
                                                                class="avatar-title bg-success-subtle text-success rounded-circle">
                                                                <i class="ri-add-line"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h6 class="fs-14 mb-0">Workspace Created</h6>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div id="collapseOne" class="accordion-collapse collapse show"
                                                aria-labelledby="headingOne" data-bs-parent="#activityTimeline">
                                                <div class="accordion-body ms-2 ps-5 pt-0">
                                                    <h6 class="mb-1">{{ $record->created_at->format('M d, Y h:i A') }}
                                                    </h6>
                                                    <p class="text-muted mb-0">
                                                        Workspace was created by
                                                        @if ($record->creator)
                                                            <strong>{{ $record->creator->first_name }}
                                                                {{ $record->creator->last_name }}</strong>
                                                        @else
                                                            <strong>System</strong>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Updated Event -->
                                        @if ($record->updated_at->gt($record->created_at))
                                            <div class="accordion-item border-0">
                                                <div class="accordion-header" id="headingTwo">
                                                    <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse"
                                                        href="#collapseTwo" aria-expanded="false">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0 avatar-xs">
                                                                <div
                                                                    class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                                                    <i class="ri-edit-line"></i>
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1 ms-3">
                                                                <h6 class="fs-14 mb-0">Last Updated</h6>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="collapseTwo" class="accordion-collapse collapse show"
                                                    aria-labelledby="headingTwo" data-bs-parent="#activityTimeline">
                                                    <div class="accordion-body ms-2 ps-5 pt-0">
                                                        <h6 class="mb-1">
                                                            {{ $record->updated_at->format('M d, Y h:i A') }}</h6>
                                                        <p class="text-muted mb-0">
                                                            Workspace was last updated
                                                            @if ($record->updater)
                                                                by <strong>{{ $record->updater->first_name }}
                                                                    {{ $record->updater->last_name }}</strong>
                                                            @endif
                                                            <span
                                                                class="text-muted">({{ $record->updated_at->diffForHumans() }})</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Questionnaire Responses -->
                                        @if ($record->questionnaireResponses->count() > 0)
                                            <div class="accordion-item border-0">
                                                <div class="accordion-header" id="headingThree">
                                                    <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse"
                                                        href="#collapseThree" aria-expanded="false">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0 avatar-xs">
                                                                <div
                                                                    class="avatar-title bg-info-subtle text-info rounded-circle">
                                                                    <i class="ri-questionnaire-line"></i>
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1 ms-3">
                                                                <h6 class="fs-14 mb-0">Questionnaire Completed</h6>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="collapseThree" class="accordion-collapse collapse show"
                                                    aria-labelledby="headingThree" data-bs-parent="#activityTimeline">
                                                    <div class="accordion-body ms-2 ps-5 pt-0">
                                                        <p class="text-muted mb-0">
                                                            Completed
                                                            <strong>{{ $record->questionnaireResponses->count() }}</strong>
                                                            questionnaire responses
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Workspace</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this workspace? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" action="{{ route('admin.workspace.destroy', $record->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Workspace</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteWorkspace(id) {
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
    </script>

@endsection
