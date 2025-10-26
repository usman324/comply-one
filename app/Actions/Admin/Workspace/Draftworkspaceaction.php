<?php

namespace App\Actions\Admin\Workspace;

use App\Actions\BaseAction;
use App\Models\WorkspaceDraft;
use App\Traits\CustomAction;
use Lorisleiva\Actions\ActionRequest;
use App\Traits\RespondsWithJson;
use Exception;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DraftWorkspaceAction extends BaseAction
{
    use AsAction;
    use RespondsWithJson;
    use CustomAction;

    protected string $title = 'Workspace Draft';
    protected string $view = 'admin.workspace';
    protected string $url = 'workspaces';
    protected string $permission = 'workspace';

    public function handle($request)
    {
        try {
            DB::beginTransaction();

            // Get or create draft for current user/session
            $draft = WorkspaceDraft::firstOrCreate(
                [
                    'user_id' => auth()->id(),
                    'session_id' => session()->getId(),
                ],
                [
                    'draft_data' => json_encode([]),
                ]
            );

            // Update draft data
            $draftData = [
                'workspace_name' => $request->workspace_name,
                'workspace_description' => $request->workspace_description,
                'workspace_type' => $request->workspace_type,
                'workspace_status' => $request->workspace_status,
                'answers' => $request->answers ?? [],
                'current_step' => $request->current_step ?? 'workspace',
                'last_saved' => now()->toDateTimeString(),
            ];

            $draft->update([
                'draft_data' => json_encode($draftData),
            ]);

            DB::commit();

            return $this->success('Draft saved successfully', [
                'draft_id' => $draft->id,
                'last_saved' => $draft->updated_at->diffForHumans(),
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request);
    }
}
