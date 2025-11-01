<?php

namespace App\Actions\Admin\Workspace\FileManager;

use App\Actions\BaseAction;
use App\Models\File;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class GetStarredFilesAction extends BaseAction
{
    use AsAction;

    protected string $title = 'Starred Files';
    protected string $view = 'admin.file-manager';
    protected string $url = 'file-managers';
    protected string $permission = 'file-manager';

    public function asController(Request $request)
    {
        $workspaceId = $request->workspace_id ?? 1;

        $files = File::where('workspace_id', $workspaceId)
            ->where('status', 'active')
            ->starred()
            ->with(['folder', 'uploader'])
            ->orderBy('updated_at', 'desc')
            ->paginate($request->per_page ?? 20);

        if ($request->expectsJson() || $request->ajax()) {
            $filesData = $files->map(function ($file) {
                return [
                    'id' => $file->id,
                    'original_name' => $file->original_name,
                    'display_name' => $file->display_name,
                    'extension' => $file->extension,
                    'file_size' => $file->formatted_size,
                    'file_icon' => $file->file_icon,
                    'mime_type' => $file->mime_type,
                    'folder_id' => $file->folder_id,
                    'folder_name' => $file->folder ? $file->folder->name : 'Root',
                    'is_starred' => $file->is_starred,
                    'description' => $file->description,
                    'tags' => $file->tags,
                    'uploaded_by' => $file->uploader ? $file->uploader->name : 'Unknown',
                    'created_at' => $file->created_at->format('d M, Y'),
                    'updated_at' => $file->updated_at->format('d M, Y'),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $filesData,
                'pagination' => [
                    'current_page' => $files->currentPage(),
                    'per_page' => $files->perPage(),
                    'total' => $files->total(),
                    'last_page' => $files->lastPage(),
                ]
            ]);
        }

        return view('file-manager.starred', compact('files'));
    }
}
