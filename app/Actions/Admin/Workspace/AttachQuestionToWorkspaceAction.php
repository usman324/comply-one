<?php

namespace App\Actions\Admin\Workspace;

use App\Actions\BaseAction;
use App\Models\Workspace;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttachQuestionToWorkspaceAction extends BaseAction
{
    protected string $title = 'Workspace';
    protected string $view = 'admin.workspace';
    protected string $url = 'workspaces';
    protected string $permission = 'workspace';

    /**
     * Attach single question to workspace
     */
    public function handle(int $workspaceId, int $questionId, array $settings = [])
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

    public function asController(Request $request, Workspace $workspace, $question)
    {
        $validated = $request->validate([
            'order' => 'nullable|integer|min:0',
            'is_required' => 'nullable|boolean',
            'workspace_specific_options' => 'nullable|array',
        ]);

        $result = $this->handle($workspace->id, $question, $validated);

        return response()->json($result, $result['success'] ? 200 : 400);
    }
}
