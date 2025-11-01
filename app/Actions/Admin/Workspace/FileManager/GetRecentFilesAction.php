<?php

namespace App\Actions\Admin\Workspace\FileManager;

use App\Actions\BaseAction;
use App\Models\File;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class GetRecentFilesAction extends BaseAction
{
    use AsAction;

    protected string $title = 'Recent Files';
    protected string $view = 'admin.file-manager';
    protected string $url = 'file-managers';
    protected string $permission = 'file-manager';

    public function asController(Request $request)
    {
        $workspaceId = $request->workspace_id ?? 1;
        $days = $request->days ?? 30; // Default to 30 days

        $files = File::where('workspace_id', $workspaceId)
            ->where('status', 'active')
            ->recent($days)
            ->with(['folder', 'uploader'])
            ->orderBy('created_at', 'desc')
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
                    'uploaded_by' => $file->uploader ? $file->uploader->name : 'Unknown',
                    'created_at' => $file->created_at->format('d M, Y H:i'),
                    'days_ago' => $file->created_at->diffForHumans(),
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

        return view('file-manager.recent', compact('files'));
    }
}
