<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveQuestionnaireResponseRequest;
use App\Services\QuestionnaireService;
use App\Models\Questionnaire;
use App\Models\Question;
use App\Models\QuestionnaireResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class QuestionnaireController extends Controller
{
    protected QuestionnaireService $questionnaireService;
    protected $url = 'questionnaires';
    protected $title = 'Questionnaire';
    protected $permission = 'questionnaire';

    public function __construct(QuestionnaireService $questionnaireService)
    {
        $this->questionnaireService = $questionnaireService;
    }

    // ==========================================
    // EXISTING METHODS (Your Original Code)
    // ==========================================

    /**
     * Get questions for a specific section.
     */
    public function getSectionQuestions(Request $request, string $section): JsonResponse
    {
        $userId = $request->user()->id;
        $questions = $this->questionnaireService->getSectionQuestionsWithAnswers($userId, $section);

        return response()->json([
            'success' => true,
            'data' => $questions,
        ]);
    }

    /**
     * Get visible questions based on dependencies.
     */
    public function getVisibleQuestions(Request $request, string $section): JsonResponse
    {
        $userId = $request->user()->id;
        $questions = $this->questionnaireService->getVisibleQuestions($userId, $section);

        return response()->json([
            'success' => true,
            'data' => $questions,
        ]);
    }

    /**
     * Save a single response.
     */
    public function saveResponse(SaveQuestionnaireResponseRequest $request): JsonResponse
    {
        $userId = $request->user()->id;
        $questionId = $request->input('question_id');
        $answer = $request->input('answer');

        $response = $this->questionnaireService->saveResponse($userId, $questionId, $answer);

        return response()->json([
            'success' => true,
            'message' => 'Response saved successfully',
            'data' => $response,
        ]);
    }

    /**
     * Save multiple responses at once.
     */
    public function saveMultipleResponses(Request $request): JsonResponse
    {
        $request->validate([
            'responses' => 'required|array',
            'responses.*.question_id' => 'required|string',
            'responses.*.answer' => 'nullable',
        ]);

        $userId = $request->user()->id;
        $responses = collect($request->input('responses'))
            ->mapWithKeys(fn ($item) => [$item['question_id'] => $item['answer']])
            ->toArray();

        $savedResponses = $this->questionnaireService->saveMultipleResponses($userId, $responses);

        return response()->json([
            'success' => true,
            'message' => 'Responses saved successfully',
            'data' => $savedResponses,
        ]);
    }

    /**
     * Get user's responses for a section.
     */
    public function getSectionResponses(Request $request, string $section): JsonResponse
    {
        $userId = $request->user()->id;
        $responses = $this->questionnaireService->getUserSectionResponses($userId, $section);

        return response()->json([
            'success' => true,
            'data' => $responses,
        ]);
    }

    /**
     * Get overall progress.
     */
    public function getProgress(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $progress = $this->questionnaireService->getOverallProgress($userId);

        return response()->json([
            'success' => true,
            'data' => $progress,
        ]);
    }

    /**
     * Get section completion status.
     */
    public function getSectionProgress(Request $request, string $section): JsonResponse
    {
        $userId = $request->user()->id;
        $percentage = $this->questionnaireService->getSectionCompletionPercentage($userId, $section);
        $isCompleted = $this->questionnaireService->isSectionCompleted($userId, $section);

        return response()->json([
            'success' => true,
            'data' => [
                'section' => $section,
                'completion_percentage' => $percentage,
                'is_completed' => $isCompleted,
            ],
        ]);
    }

    /**
     * Validate section completion.
     */
    public function validateSection(Request $request, string $section): JsonResponse
    {
        $userId = $request->user()->id;
        $validation = $this->questionnaireService->validateSectionCompletion($userId, $section);

        return response()->json([
            'success' => true,
            'data' => $validation,
        ]);
    }

    /**
     * Export section responses.
     */
    public function exportSection(Request $request, string $section): JsonResponse
    {
        $userId = $request->user()->id;
        $export = $this->questionnaireService->exportSectionResponses($userId, $section);

        return response()->json([
            'success' => true,
            'data' => $export,
        ]);
    }

    /**
     * Delete a response (from existing service).
     */
    public function deleteResponseService(Request $request, string $questionId): JsonResponse
    {
        $userId = $request->user()->id;
        $deleted = $this->questionnaireService->deleteResponse($userId, $questionId);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Response deleted successfully',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Response not found',
        ], 404);
    }

    // ==========================================
    // NEW CRUD METHODS (For Admin Management)
    // ==========================================

    /**
     * Display a listing of questionnaires (Admin view)
     */
    public function index(Request $request)
    {
        if ($request->table == 1) {
            $data = Questionnaire::latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($record) {
                    return view('admin.questionnaire.include.actions', [
                        'record' => $record,
                        'url' => $this->url,
                        'permission' => $this->permission
                    ])->render();
                })
                ->addColumn('questions_count', function ($record) {
                    return $record->questions->count();
                })
                ->addColumn('responses_count', function ($record) {
                    return $record->responses->count();
                })
                ->addColumn('status', function ($record) {
                    $badge = $record->status === 'active' ? 'success' : 'secondary';
                    return '<span class="badge bg-' . $badge . '">' . ucfirst($record->status) . '</span>';
                })
                ->addColumn('created_at', function ($record) {
                    return $record->created_at->format('M d, Y');
                })
                ->rawColumns(['actions', 'status'])
                ->make(true);
        }

        return view('admin.questionnaire.index', [
            'url' => $this->url,
            'title' => $this->title,
            'permission' => $this->permission
        ]);
    }

    /**
     * Show the form for creating a new questionnaire
     */
    public function create()
    {
        return view('admin.questionnaire.create', [
            'url' => url($this->url),
            'title' => $this->title
        ]);
    }

    /**
     * Store a newly created questionnaire
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'section' => 'required|string',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'questions' => 'required|json'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Create questionnaire
            $questionnaire = Questionnaire::create([
                'user_id' => auth()->user()->id,
                'title' => $request->title,
                'section' => $request->section,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'allow_anonymous' => $request->has('allow_anonymous'),
                'allow_multiple_responses' => $request->has('allow_multiple_responses'),
                'show_progress' => $request->has('show_progress'),
                'randomize_questions' => $request->has('randomize_questions'),
                'status' => 'active',
            ]);

            // Create questions
            $questions = json_decode($request->questions, true);

            foreach ($questions as $index => $questionData) {
                Question::create([
                    'questionnaire_id' => $questionnaire->id,
                    'question' => $questionData['question'],
                    'type' => $questionData['type'],
                    'description' => $questionData['description'] ?? null,
                    'options' => isset($questionData['options']) ? json_encode($questionData['options']) : null,
                    'is_required' => $questionData['is_required'] ?? false,
                    'order' => $index + 1
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Questionnaire created successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create questionnaire: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the questionnaire for taking (User view)
     */
    public function take($id)
    {
        $questionnaire = Questionnaire::with(['questions' => function ($query) {
            $query->orderBy('order');
        }])->findOrFail($id);

        // Check if questionnaire is active
        if ($questionnaire->status !== 'active') {
            abort(403, 'This questionnaire is not currently available.');
        }

        // Check date range
        if ($questionnaire->start_date && now()->lt($questionnaire->start_date)) {
            abort(403, 'This questionnaire has not started yet.');
        }

        if ($questionnaire->end_date && now()->gt($questionnaire->end_date)) {
            abort(403, 'This questionnaire has ended.');
        }

        // Check if user already responded (if multiple responses not allowed)
        if (!$questionnaire->allow_multiple_responses && auth()->check()) {
            $hasResponded = QuestionnaireResponse::where('questionnaire_id', $id)
                ->where('user_id', auth()->user()->id)
                ->exists();

            if ($hasResponded) {
                return redirect()->back()->with('error', 'You have already responded to this questionnaire.');
            }
        }

        // Randomize questions if enabled
        $questions = $questionnaire->questions;
        if ($questionnaire->randomize_questions) {
            $questions = $questions->shuffle();
        }

        // Parse options for each question
        foreach ($questions as $question) {
            if ($question->options && is_string($question->options)) {
                $question->options = json_decode($question->options, true);
            }
        }

        return view('admin.questionnaire.take', [
            'questionnaire' => $questionnaire,
            'questions' => $questions,
            'url' => $this->url,
            'title' => $this->title
        ]);
    }

    /**
     * Submit questionnaire response
     */
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'questionnaire_id' => 'required|exists:questionnaires,id',
            'answers' => 'required|array',
            'time_spent' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $questionnaire = Questionnaire::findOrFail($request->questionnaire_id);

            // Save answers directly as QuestionnaireResponse records
            foreach ($request->answers as $questionId => $answer) {
                // Handle file uploads
                if ($request->hasFile("answers.{$questionId}")) {
                    $file = $request->file("answers.{$questionId}");
                    $path = $file->store('questionnaire-responses', 'public');
                    $answer = $path;
                }

                // Handle array answers (checkboxes)
                if (is_array($answer)) {
                    $answer = json_encode($answer);
                }

                QuestionnaireResponse::create([
                    'questionnaire_id' => $request->questionnaire_id,
                    'question_id' => $questionId,
                    'user_id' => $questionnaire->allow_anonymous ? null : auth()->user()->id,
                    'section' => $questionnaire->section,
                    'answer' => $answer,
                    'answered_at' => now(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your response!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit response: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save draft response
     */
    public function saveDraft(Request $request)
    {
        try {
            $questionnaire = Questionnaire::findOrFail($request->questionnaire_id);

            // Save current answers as draft responses
            foreach ($request->answers ?? [] as $questionId => $answer) {
                if (is_array($answer)) {
                    $answer = json_encode($answer);
                }

                QuestionnaireResponse::updateOrCreate(
                    [
                        'questionnaire_id' => $request->questionnaire_id,
                        'question_id' => $questionId,
                        'user_id' => auth()->user()->id,
                    ],
                    [
                        'section' => $questionnaire->section,
                        'answer' => $answer,
                        'answered_at' => now(),
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent()
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Draft saved successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save draft: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing questionnaire
     */
    public function edit($id)
    {
        $record = Questionnaire::with('questions')->findOrFail($id);

        return view('admin.questionnaire.edit', [
            'record' => $record,
            'url' => $this->url,
            'title' => $this->title
        ]);
    }

    /**
     * Update questionnaire
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'section' => 'required|string',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $questionnaire = Questionnaire::findOrFail($id);
            $questionnaire->update($request->only([
                'title',
                'section',
                'description',
                'status',
                'start_date',
                'end_date'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Questionnaire updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update questionnaire: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete questionnaire
     */
    public function destroy($id)
    {
        try {
            $questionnaire = Questionnaire::findOrFail($id);
            $questionnaire->delete();

            return response()->json([
                'success' => true,
                'message' => 'Questionnaire deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete questionnaire: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View questionnaire results
     */
    public function results($id)
    {
        $questionnaire = Questionnaire::with(['questions', 'responses'])->findOrFail($id);

        $analytics = [];
        foreach ($questionnaire->questions as $question) {
            $answers = QuestionnaireResponse::where('question_id', $question->id)->get();

            $analytics[$question->id] = [
                'question' => $question->question,
                'type' => $question->type,
                'total_responses' => $answers->count(),
                'answers' => $answers
            ];

            // Calculate statistics based on question type
            if ($question->type === 'rating' || $question->type === 'scale') {
                $values = $answers->pluck('answer')->filter()->map(fn ($v) => (float)$v);
                $analytics[$question->id]['average'] = $values->avg();
                $analytics[$question->id]['min'] = $values->min();
                $analytics[$question->id]['max'] = $values->max();
            }

            if (in_array($question->type, ['radio', 'checkbox', 'select'])) {
                $analytics[$question->id]['distribution'] = $answers
                    ->groupBy('answer')
                    ->map(fn ($group) => $group->count())
                    ->toArray();
            }
        }

        return view('admin.questionnaire.results', [
            'questionnaire' => $questionnaire,
            'analytics' => $analytics,
            'title' => $this->title
        ]);
    }
}
