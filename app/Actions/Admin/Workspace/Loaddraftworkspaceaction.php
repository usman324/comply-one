<?php

namespace App\Actions\Admin\Workspace;

use App\Actions\BaseAction;
use App\Models\WorkspaceDraft;
use App\Traits\CustomAction;
use Lorisleiva\Actions\ActionRequest;
use App\Traits\RespondsWithJson;
use Lorisleiva\Actions\Concerns\AsAction;

class LoadDraftWorkspaceAction extends BaseAction
{
    use AsAction;
    use RespondsWithJson;
    use CustomAction;

    protected string $title = 'Workspace Draft';
    protected string $view = 'admin.workspace';
    protected string $url = 'workspaces';
    protected string $permission = 'workspace';

    public function handle()
    {
        try {
            // Get draft for current user/session
            $draft = WorkspaceDraft::where('user_id', auth()->id())
                ->orWhere('session_id', session()->getId())
                ->latest()
                ->first();

            if ($draft) {
                return $this->success('Draft loaded successfully', [
                    'draft' => json_decode($draft->draft_data, true),
                    'last_saved' => $draft->updated_at->diffForHumans(),
                ]);
            }

            return $this->success('No draft found', ['draft' => null]);

        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle();
    }
}
