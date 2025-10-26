<?php

namespace App\Actions\Admin\Workspace;

use App\Actions\BaseAction;
use App\Models\Workspace;
use App\Traits\CustomAction;
use Lorisleiva\Actions\ActionRequest;
use App\Traits\RespondsWithJson;
use Exception;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteWorkspaceAction extends BaseAction
{
    use AsAction;
    use RespondsWithJson;
    use CustomAction;

    protected string $title = 'Workspace';
    protected string $view = 'admin.workspace';
    protected string $url = 'workspaces';
    protected string $permission = 'workspace';

    public function handle(int $id)
    {
        try {
            $workspace = Workspace::findOrFail($id);

            // Delete avatar if exists
            if ($workspace->avatar) {
                deleteImage('workspace/' . $workspace->avatar, $workspace->avatar);
            }

            // Delete workspace (this will cascade delete questionnaire_responses)
            $workspace->delete();

            return $this->success('Workspace deleted successfully');
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function asController(ActionRequest $request, $id)
    {
        return $this->handle($id);
    }
}
