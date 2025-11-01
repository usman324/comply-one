<?php

namespace App\Actions\Admin\Workspace\Folder;

use App\Actions\BaseAction;
use App\Models\Workspace;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\ActionRequest;

class StoreFolderAction extends BaseAction
{
    protected string $title = 'Folder';
    protected string $view = 'admin.workspace.file';
    protected string $url = 'folders';
    protected string $permission = 'folder';

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'workspace_id' => 'required|integer|exists:workspaces,id',
            'parent_folder_id' => 'nullable|integer|exists:folders,id',
        ];
    }

    public function handle(ActionRequest $request, Workspace $workspace)
    {
        try {
            $folder = $workspace->folders()->create([
                'parent_folder_id' => $request->parent_folder_id,
                'created_by_user_id' => Auth::id() ?? 1,
                'name' => $request->name,
                'description' => $request->description,
                'status' => 'active',
            ]);

            return  $this->success(
                'Folder created successfully',
                [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'description' => $folder->description,
                    'parent_folder_id' => $folder->parent_folder_id,
                    'file_count' => 0,
                    'folder_size' => '0 B',
                    'created_at' => $folder->created_at->format('d M, Y'),
                ]
            );
        } catch (\Exception $e) {
            return $this->error('Failed to create folder: ' . $e->getMessage());
        }
    }

    public function asController(ActionRequest $request, Workspace $workspace)
    {
        return $this->handle($request, $workspace);

    }
}
