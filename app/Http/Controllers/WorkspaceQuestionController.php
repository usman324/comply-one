<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Models\Question;
use App\Actions\Admin\Workspace\AssignQuestionsToWorkspaceAction;
use App\Models\Questionnaire;
use Illuminate\Http\Request;

class WorkspaceQuestionController extends Controller
{
    protected $assignAction;

    public function __construct(AssignQuestionsToWorkspaceAction $assignAction)
    {
        $this->assignAction = $assignAction;
    }

    /**
     * Show form to assign questions to workspace
     */
    public function showAssignForm(Workspace $workspace)
    {
        $workspace->load(['questions']);

        // Get all available questions
        $availableQuestions = Question::with(['questionnaire', 'workspaces'])
            ->ordered()
            ->get();

        // Get all questionnaires for filtering
        $questionnaires = Questionnaire::orderBy('title')->get();

        return view('admin.workspace.assign-questions', [
            'workspace' => $workspace,
            'availableQuestions' => $availableQuestions,
            'questionnaires' => $questionnaires,
        ]);
    }

    /**
     * Update question settings for workspace
     */
    public function updateQuestionSettings(Request $request, Workspace $workspace, Question $question)
    {
        $validated = $request->validate([
            'order' => 'nullable|integer|min:0',
            'is_required' => 'nullable|boolean',
            'workspace_specific_options' => 'nullable|array',
        ]);

        $result = $this->assignAction->updateQuestionSettings(
            $workspace->id,
            $question->id,
            $validated
        );

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Detach question from workspace
     */
    public function detachQuestion(Workspace $workspace, Question $question)
    {
        $result = $this->assignAction->detachQuestion($workspace->id, $question->id);

        if (request()->wantsJson()) {
            return response()->json($result, $result['success'] ? 200 : 400);
        }

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Attach single question to workspace
     */
    public function attachQuestion(Request $request, Workspace $workspace, Question $question)
    {
        $validated = $request->validate([
            'order' => 'nullable|integer|min:0',
            'is_required' => 'nullable|boolean',
            'workspace_specific_options' => 'nullable|array',
        ]);

        $result = $this->assignAction->attachQuestion(
            $workspace->id,
            $question->id,
            $validated
        );

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Reorder questions in workspace
     */
    public function reorderQuestions(Request $request, Workspace $workspace)
    {
        $validated = $request->validate([
            'questions' => 'required|array',
            'questions.*' => 'exists:questions,id',
        ]);

        $result = $this->assignAction->reorderQuestions(
            $workspace->id,
            $validated['questions']
        );

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Bulk assign questions to multiple workspaces
     */
    public function bulkAssign(Request $request)
    {
        $validated = $request->validate([
            'workspace_ids' => 'required|array',
            'workspace_ids.*' => 'exists:workspaces,id',
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:questions,id',
            'default_settings' => 'nullable|array',
            'default_settings.is_required' => 'nullable|boolean',
        ]);

        $result = $this->assignAction->bulkAssignToWorkspaces(
            $validated['workspace_ids'],
            $validated['question_ids'],
            $validated['default_settings'] ?? []
        );

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Copy questions from one workspace to another
     */
    public function copyQuestions(Workspace $sourceWorkspace, Workspace $targetWorkspace)
    {
        $result = $this->assignAction->copyQuestionsFromWorkspace(
            $targetWorkspace->id,
            $sourceWorkspace->id
        );

        if (request()->wantsJson()) {
            return response()->json($result, $result['success'] ? 200 : 400);
        }

        if ($result['success']) {
            return redirect()->route('admin.workspace.show', $targetWorkspace)
                ->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Get questions for a workspace (API endpoint)
     */
    public function getWorkspaceQuestions(Workspace $workspace)
    {
        $questions = $workspace->questions()
            ->with('questionnaire')
            ->get()
            ->map(function ($question) {
                return [
                    'id' => $question->id,
                    'question' => $question->question,
                    'type' => $question->type,
                    'questionnaire' => $question->questionnaire?->name,
                    'order' => $question->pivot->order,
                    'is_required' => $question->pivot->is_required || $question->is_required,
                    'workspace_specific_options' => json_decode($question->pivot->workspace_specific_options, true),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $questions,
        ]);
    }

    /**
     * Get available questions not assigned to workspace
     */
    public function getAvailableQuestions(Workspace $workspace)
    {
        $assignedQuestionIds = $workspace->questions()->pluck('questions.id');

        $questions = Question::with('questionnaire')
            ->whereNotIn('id', $assignedQuestionIds)
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $questions,
        ]);
    }
}
