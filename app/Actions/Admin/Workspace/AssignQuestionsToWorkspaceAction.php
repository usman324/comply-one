<?php

namespace App\Actions\Admin\Workspace;

use App\Actions\BaseAction;
use App\Models\Workspace;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssignQuestionsToWorkspaceAction extends BaseAction
{
    protected string $title = 'Workspace';
    protected string $view = 'admin.workspace';
    protected string $url = 'workspaces';
    protected string $permission = 'workspace';
    /**
     * Assign questions to workspace
     *
     * @param  int  $workspaceId
     * @param  array  $questionsData
     * @return array
     */
    public function handle(int $workspaceId, array $questionsData)
    {
        try {
            DB::beginTransaction();

            $workspace = Workspace::findOrFail($workspaceId);

            // Prepare sync data with pivot attributes
            $syncData = [];
            foreach ($questionsData as $index => $questionData) {
                $questionId = is_array($questionData) ? ($questionData['id'] ?? $questionData['question_id']) : $questionData;

                // Verify question exists
                if (!Question::find($questionId)) {
                    continue;
                }

                $syncData[$questionId] = [
                    'order' => $questionData['order'] ?? $index,
                    'is_required' => $questionData['is_required'] ?? false,
                    'workspace_specific_options' => isset($questionData['workspace_specific_options'])
                        ? json_encode($questionData['workspace_specific_options'])
                        : null,
                ];
            }

            // Sync questions with workspace
            $workspace->questions()->sync($syncData);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Questions assigned successfully',
                'data' => [
                    'workspace' => $workspace->fresh()->load('questions'),
                    'assigned_count' => count($syncData),
                ]
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error assigning questions to workspace: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to assign questions: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Handle as controller
     */
    public function asController(Request $request, $workspaceId)
    {
        $validated = $request->validate([
            'questions' => 'required|array',
            'questions.*.id' => 'required|exists:questions,id',
            'questions.*.order' => 'nullable|integer',
            // 'questions.*.is_required' => 'nullable|boolean',
            'questions.*.workspace_specific_options' => 'nullable|array',
        ]);

        $result = $this->handle($workspaceId, $validated['questions']);

        if ($result['success']) {
            return response()->json($result, 200);
        }

        return response()->json($result, 400);
    }

    /**
     * Attach single question to workspace
     */
    public function attachQuestion(int $workspaceId, int $questionId, array $settings = [])
    {
        try {
            $workspace = Workspace::findOrFail($workspaceId);
            $question = Question::findOrFail($questionId);

            $workspace->questions()->attach($questionId, [
                'order' => $settings['order'] ?? $workspace->questions()->count(),
                'is_required' => $settings['is_required'] ?? false,
                'workspace_specific_options' => isset($settings['workspace_specific_options'])
                    ? json_encode($settings['workspace_specific_options'])
                    : null,
            ]);

            return [
                'success' => true,
                'message' => 'Question attached successfully',
            ];

        } catch (\Exception $e) {
            Log::error('Error attaching question: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to attach question: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Detach question from workspace
     */
    public function detachQuestion(int $workspaceId, int $questionId)
    {
        try {
            $workspace = Workspace::findOrFail($workspaceId);
            $workspace->questions()->detach($questionId);

            return [
                'success' => true,
                'message' => 'Question removed successfully',
            ];

        } catch (\Exception $e) {
            Log::error('Error detaching question: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to remove question: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Update question settings for workspace
     */
    public function updateQuestionSettings(int $workspaceId, int $questionId, array $settings)
    {
        try {
            $workspace = Workspace::findOrFail($workspaceId);

            $workspace->questions()->updateExistingPivot($questionId, [
                'order' => $settings['order'] ?? null,
                'is_required' => $settings['is_required'] ?? null,
                'workspace_specific_options' => isset($settings['workspace_specific_options'])
                    ? json_encode($settings['workspace_specific_options'])
                    : null,
            ]);

            return [
                'success' => true,
                'message' => 'Question settings updated successfully',
            ];

        } catch (\Exception $e) {
            Log::error('Error updating question settings: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to update settings: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Reorder questions in workspace
     */
    public function reorderQuestions(int $workspaceId, array $questionOrder)
    {
        try {
            DB::beginTransaction();

            $workspace = Workspace::findOrFail($workspaceId);

            foreach ($questionOrder as $order => $questionId) {
                $workspace->questions()->updateExistingPivot($questionId, [
                    'order' => $order,
                ]);
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Questions reordered successfully',
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error reordering questions: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to reorder questions: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Copy questions from one workspace to another
     */
    public function copyQuestionsFromWorkspace(int $targetWorkspaceId, int $sourceWorkspaceId)
    {
        try {
            DB::beginTransaction();

            $targetWorkspace = Workspace::findOrFail($targetWorkspaceId);
            $sourceWorkspace = Workspace::findOrFail($sourceWorkspaceId);

            $sourceQuestions = $sourceWorkspace->questions()
                ->get()
                ->map(function ($question) {
                    return [
                        'id' => $question->id,
                        'order' => $question->pivot->order,
                        'is_required' => $question->pivot->is_required,
                        'workspace_specific_options' => $question->pivot->workspace_specific_options,
                    ];
                })
                ->toArray();

            $result = $this->handle($targetWorkspaceId, $sourceQuestions);

            DB::commit();

            return $result;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error copying questions: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to copy questions: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Bulk assign questions to multiple workspaces
     */
    public function bulkAssignToWorkspaces(array $workspaceIds, array $questionIds, array $defaultSettings = [])
    {
        try {
            DB::beginTransaction();

            $results = [];
            foreach ($workspaceIds as $workspaceId) {
                $questionsData = array_map(function ($questionId) use ($defaultSettings) {
                    return array_merge(['id' => $questionId], $defaultSettings);
                }, $questionIds);

                $results[$workspaceId] = $this->handle($workspaceId, $questionsData);
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Questions assigned to multiple workspaces',
                'data' => $results,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error bulk assigning questions: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to bulk assign questions: ' . $e->getMessage(),
            ];
        }
    }
}
