<?php

namespace App\Actions\Admin\Workspace;

use App\Actions\BaseAction;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UpdateQuestionSettingsAction extends BaseAction
{
    protected string $title = 'Workspace';
    protected string $view = 'admin.workspace';
    protected string $url = 'workspaces';
    protected string $permission = 'workspace';

    /**
     * Update question settings for workspace
     */
    public function handle(int $workspaceId, int $questionId, array $settings)
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
