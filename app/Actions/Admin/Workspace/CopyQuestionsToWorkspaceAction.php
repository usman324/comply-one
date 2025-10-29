<?php

namespace App\Actions\Admin\Workspace;

use App\Actions\BaseAction;
use App\Models\Workspace;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CopyQuestionsToWorkspaceAction extends BaseAction
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
     * Copy questions from one workspace to another
     */
    public function handle(int $targetWorkspaceId, int $sourceWorkspaceId)
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

            $result = $this->assignAction->handle($targetWorkspaceId, $sourceQuestions);

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

    public function asController(Workspace $sourceWorkspace, Workspace $targetWorkspace)
    {
        $result = $this->handle($targetWorkspace->id, $sourceWorkspace->id);

        if (request()->wantsJson()) {
            return response()->json($result, $result['success'] ? 200 : 400);
        }

        if ($result['success']) {
            return redirect()->route('admin.workspace.show', $targetWorkspace)
                ->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }
}
