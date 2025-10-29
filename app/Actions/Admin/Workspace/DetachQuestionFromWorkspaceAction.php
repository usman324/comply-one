<?php

namespace App\Actions\Admin\Workspace;

use App\Actions\BaseAction;
use App\Models\Workspace;
use Illuminate\Support\Facades\Log;

class DetachQuestionFromWorkspaceAction extends BaseAction
{
    protected string $title = 'Workspace';
    protected string $view = 'admin.workspace';
    protected string $url = 'workspaces';
    protected string $permission = 'workspace';

    /**
     * Detach question from workspace
     */
    public function handle(int $workspaceId, int $questionId)
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

    public function asController(Workspace $workspace, $question)
    {
        $result = $this->handle($workspace->id, $question);

        if (request()->wantsJson()) {
            return response()->json($result, $result['success'] ? 200 : 400);
        }

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }
}
