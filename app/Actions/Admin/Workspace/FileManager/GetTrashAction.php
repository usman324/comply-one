<?php

namespace App\Actions\Admin\Workspace\FileManager;

use App\Actions\BaseAction;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class GetTrashAction extends BaseAction
{
    use AsAction;

    protected string $title = 'Trash';
    protected string $view = 'admin.file-manager';
    protected string $url = 'file-managers';
    protected string $permission = 'file-manager';

    public function asController(Request $request)
    {
        $workspaceId = $request->workspace_id ?? 1;

        $deletedFolders = Folder::where('workspace_id', $workspaceId)
            ->where('status', 'deleted')
            ->withTrashed()
            ->get();

        $deletedFiles = File::where('workspace_id', $workspaceId)
            ->where('status', 'deleted')
            ->withTrashed()
            ->with(['folder', 'uploader'])
            ->paginate($request->per_page ?? 20);

        if ($request->expectsJson() || $request->ajax()) {
            $filesData = $deletedFiles->map(function ($file) {
                return [
                    'id' => $file->id,
                    'original_name' => $file->original_name,
                    'display_name' => $file->display_name,
                    'file_size' => $file->formatted_size,
                    'folder_name' => $file->folder ? $file->folder->name : 'Root',
                    'deleted_at' => $file->deleted_at ? $file->deleted_at->format('d M, Y') : null,
                    'uploaded_by' => $file->uploader ? $file->uploader->name : 'Unknown',
                ];
            });

            $foldersData = $deletedFolders->map(function ($folder) {
                return [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'deleted_at' => $folder->deleted_at ? $folder->deleted_at->format('d M, Y') : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'folders' => $foldersData,
                    'files' => $filesData,
                ],
                'pagination' => [
                    'current_page' => $deletedFiles->currentPage(),
                    'per_page' => $deletedFiles->perPage(),
                    'total' => $deletedFiles->total(),
                    'last_page' => $deletedFiles->lastPage(),
                ]
            ]);
        }

        return view('file-manager.trash', compact('deletedFolders', 'deletedFiles'));
    }
}
