<?php

namespace App\Actions\Admin\Workspace;

use App\Actions\BaseAction;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReorderWorkspaceQuestionsAction extends BaseAction
{
    protected string $title = 'Workspace';
    protected string $view = 'admin.workspace';
    protected string $url = 'workspaces';
    protected string $permission = 'workspace';

    /**
     * Reorder questions in workspace
     */
    public function handle(int $workspaceId, array $questionOrder)
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

    public function asController(Request $request, Workspace $workspace)
    {
        $validated = $request->validate([
            'questions' => 'required|array',
            'questions.*' => 'exists:questions,id',
        ]);

        $result = $this->handle($workspace->id, $validated['questions']);

        return response()->json($result, $result['success'] ? 200 : 400);
    }
}
