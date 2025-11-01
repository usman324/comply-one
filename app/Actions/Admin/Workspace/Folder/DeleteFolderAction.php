<?php

namespace App\Actions\Admin\Workspace\Folder;

use App\Actions\BaseAction;
use App\Models\Folder;
use App\Traits\CustomAction;
use App\Traits\RespondsWithJson;
use Exception;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteFolderAction extends BaseAction
{
    use AsAction;
    use RespondsWithJson;
    use CustomAction;

    protected string $title = 'Folder';
    protected string $view = 'admin.workspace.file';
    protected string $url = 'folders';
    protected string $permission = 'folder';

    public function handle(int $id): JsonResponse
    {
        try {
            $folder = Folder::find($id);

            if (!$folder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Folder not found'
                ], 404);
            }

            // Soft delete - updates status and deleted_at
            $folder->status = 'deleted';
            $folder->deleted_at = now();
            $folder->save();

            // Also soft delete all files in folder
            $folder->files()->update([
                'status' => 'deleted',
                'deleted_at' => now()
            ]);

            return $this->success('Folder deleted successfully');
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function asController(ActionRequest $request, $id)
    {
        return $this->handle($id);
    }
}
