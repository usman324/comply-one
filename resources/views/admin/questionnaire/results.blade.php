@extends('layout.master')
@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1">{{ $questionnaire->title }}</h4>
                                <p class="text-muted mb-0">{{ $questionnaire->description }}</p>
                            </div>
                            <a href="{{ url('questionnaires') }}" class="btn btn-outline-secondary">
                                <i class="ri-arrow-left-line me-1"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Total Responses</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-3">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                    {{ $questionnaire->responses->count() }}
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-success text-success rounded fs-3">
                                    <i class="ri-file-list-3-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Total Questions</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-3">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                    {{ $questionnaire->questions->count() }}
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-info text-info rounded fs-3">
                                    <i class="ri-question-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Avg. Completion Time</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-3">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                    {{ round($questionnaire->responses->avg('time_spent') / 60, 1) }} min
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-warning text-warning rounded fs-3">
                                    <i class="ri-time-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Completion Rate</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-3">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                    {{ $questionnaire->responses->count() > 0 ? round(($questionnaire->completedResponses->count() / $questionnaire->responses->count()) * 100) : 0 }}%
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-primary text-primary rounded fs-3">
                                    <i class="ri-percent-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Question Analytics -->
        <div class="row">
            <div class="col-lg-12">
                @foreach ($analytics as $questionId => $data)
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <span class="badge bg-primary me-2">Q{{ $loop->iteration }}</span>
                                {{ $data['question'] }}
                            </h6>
                            <small class="text-muted">
                                Type: <span class="badge bg-soft-info text-info">{{ ucfirst($data['type']) }}</span> |
                                Responses: <strong>{{ $data['total_responses'] }}</strong>
                            </small>
                        </div>
                        <div class="card-body">
                            @if (in_array($data['type'], ['radio', 'checkbox', 'select']))
                                <!-- Choice Questions -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <canvas id="chart_{{ $questionId }}"></canvas>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Option</th>
                                                        <th>Count</th>
                                                        <th>Percentage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($data['distribution'] as $option => $count)
                                                        <tr>
                                                            <td>{{ $option }}</td>
                                                            <td>{{ $count }}</td>
                                                            <td>
                                                                {{ $data['total_responses'] > 0 ? round(($count / $data['total_responses']) * 100, 1) : 0 }}%
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @elseif(in_array($data['type'], ['rating', 'scale']))
                                <!-- Rating/Scale Questions -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <canvas id="chart_{{ $questionId }}"></canvas>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="stats-box p-4">
                                            <div class="row text-center">
                                                <div class="col-4">
                                                    <h3 class="text-primary">{{ round($data['average'], 2) }}</h3>
                                                    <p class="text-muted mb-0">Average</p>
                                                </div>
                                                <div class="col-4">
                                                    <h3 class="text-success">{{ $data['max'] }}</h3>
                                                    <p class="text-muted mb-0">Highest</p>
                                                </div>
                                                <div class="col-4">
                                                    <h3 class="text-danger">{{ $data['min'] }}</h3>
                                                    <p class="text-muted mb-0">Lowest</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif(in_array($data['type'], ['text', 'textarea']))
                                <!-- Text Questions -->
                                <div class="responses-list" style="max-height: 400px; overflow-y: auto;">
                                    @foreach ($data['answers'] as $answer)
                                        <div class="response-item p-3 mb-2 bg-light rounded">
                                            <p class="mb-1">{{ $answer->answer }}</p>
                                            <small class="text-muted">
                                                <i class="ri-time-line me-1"></i>
                                                {{ $answer->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Export Options -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="mb-3">Export Results</h6>
                        <button class="btn btn-success me-2" onclick="exportToExcel()">
                            <i class="ri-file-excel-line me-1"></i> Export to Excel
                        </button>
                        <button class="btn btn-danger" onclick="exportToPDF()">
                            <i class="ri-file-pdf-line me-1"></i> Export to PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            // Render charts for choice questions
            @foreach ($analytics as $questionId => $data)
                @if (in_array($data['type'], ['radio', 'checkbox', 'select']))
                    const ctx_{{ $questionId }} = document.getElementById('chart_{{ $questionId }}')
                        .getContext('2d');
                    new Chart(ctx_{{ $questionId }}, {
                        type: 'doughnut',
                        data: {
                            labels: {!! json_encode(array_keys($data['distribution'])) !!},
                            datasets: [{
                                data: {!! json_encode(array_values($data['distribution'])) !!},
                                backgroundColor: [
                                    '#0d6efd',
                                    '#6610f2',
                                    '#6f42c1',
                                    '#d63384',
                                    '#dc3545',
                                    '#fd7e14',
                                    '#ffc107',
                                    '#20c997',
                                    '#0dcaf0'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                @elseif(in_array($data['type'], ['rating', 'scale']))
                    const ctx_{{ $questionId }} = document.getElementById('chart_{{ $questionId }}')
                        .getContext('2d');

                    // Calculate distribution
                    const ratings = {!! json_encode($data['answers']->pluck('answer')->toArray()) !!};
                    const distribution = {};
                    const maxValue = {{ $data['type'] === 'rating' ? 5 : 10 }};

                    for (let i = 1; i <= maxValue; i++) {
                        distribution[i] = 0;
                    }

                    ratings.forEach(rating => {
                        if (distribution[rating] !== undefined) {
                            distribution[rating]++;
                        }
                    });

                    new Chart(ctx_{{ $questionId }}, {
                        type: 'bar',
                        data: {
                            labels: Object.keys(distribution),
                            datasets: [{
                                label: 'Number of Responses',
                                data: Object.values(distribution),
                                backgroundColor: '#0d6efd'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                @endif
            @endforeach
        });

        function exportToExcel() {
            window.location.href = '{{ url("questionnaires/{$questionnaire->id}/export/excel") }}';
        }

        function exportToPDF() {
            window.location.href = '{{ url("questionnaires/{$questionnaire->id}/export/pdf") }}';
        }
    </script>

    <style>
        .stats-box {
            background: #f8f9fa;
            border-radius: 8px;
        }

        .response-item {
            transition: all 0.2s ease;
        }

        .response-item:hover {
            background-color: #e9ecef !important;
        }

        canvas {
            max-height: 300px;
        }

        .card-animate {
            transition: all 0.3s ease;
        }

        .card-animate:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
    </style>
@endsection
