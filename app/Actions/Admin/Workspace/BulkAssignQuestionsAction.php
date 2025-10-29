<?php

namespace App\Actions\Admin\Workspace;

use App\Actions\BaseAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BulkAssignQuestionsAction extends BaseAction
{
    protected string $title = 'Workspace';
    protected string $view = 'admin.workspace';
    protected string $url = 'workspaces';
    protected string $permission = 'workspace';

    protected $assignAction;

    public function __construct(AssignQuestionsToWorkspaceAction $assignAction)
    {
        parent::__construct();
        $this->assignAction = $assignAction;
    }

    /**
     * Bulk assign questions to multiple workspaces
     */
    public function handle(array $workspaceIds, array $questionIds, array $defaultSettings = [])
    {
        try {
            DB::beginTransaction();

            $results = [];
            foreach ($workspaceIds as $workspaceId) {
                $questionsData = array_map(function ($questionId) use ($defaultSettings) {
                    return array_merge(['id' => $questionId], $defaultSettings);
                }, $questionIds);

                $results[$workspaceId] = $this->assignAction->handle($workspaceId, $questionsData);
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

    public function asController(Request $request)
    {
        $validated = $request->validate([
            'workspace_ids' => 'required|array',
            'workspace_ids.*' => 'exists:workspaces,id',
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:questions,id',
            'default_settings' => 'nullable|array',
            'default_settings.is_required' => 'nullable|boolean',
        ]);

        $result = $this->handle(
            $validated['workspace_ids'],
            $validated['question_ids'],
            $validated['default_settings'] ?? []
        );

        return response()->json($result, $result['success'] ? 200 : 400);
    }
}
