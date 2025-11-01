<?php

namespace App\Actions\Admin\Workspace\Folder;

use App\Actions\BaseAction;
use App\Models\Folder;
use App\Traits\CustomAction;
use App\Traits\RespondsWithJson;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateFolderAction extends BaseAction
{
    use AsAction;
    use RespondsWithJson;
    use CustomAction;

    protected string $title = 'Folder';
    protected string $view = 'admin.workspace.file';
    protected string $url = 'folders';
    protected string $permission = 'folder';

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'parent_folder_id' => 'nullable|integer|exists:folders,id',
        ];
    }

    public function handle($request, int $id)
    {
        $folder = Folder::find($id);

        if (!$folder) {
            return response()->json([
                'success' => false,
                'message' => 'Folder not found'
            ], 404);
        }

        // Prevent moving folder into itself or its children
        if ($request->has('parent_folder_id') && $request->parent_folder_id) {
            if ($request->parent_folder_id == $id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot move folder into itself'
                ], 422);
            }
        }

        try {
            $folder->update($request->only(['name', 'description', 'parent_folder_id']));

            return response()->json([
                'success' => true,
                'message' => 'Folder updated successfully',
                'data' => [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'description' => $folder->description,
                    'parent_folder_id' => $folder->parent_folder_id,
                    'updated_at' => $folder->updated_at->format('d M, Y'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update folder: ' . $e->getMessage()
            ], 500);
        }
    }

    public function asController(ActionRequest $request, $id)
    {
        return $this->handle($request, $id);
    }
}
