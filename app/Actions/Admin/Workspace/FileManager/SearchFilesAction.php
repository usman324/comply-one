<?php

namespace App\Actions\Admin\Workspace\FileManager;

use App\Actions\BaseAction;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class SearchFilesAction extends BaseAction
{
    use AsAction;

    protected string $title = 'Search Files';
    protected string $view = 'admin.file-manager';
    protected string $url = 'file-managers';
    protected string $permission = 'file-manager';

    public function asController(Request $request)
    {
        $workspaceId = $request->workspace_id ?? 1;
        $query = $request->query('q') ?? $request->search;

        if (!$query) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search query is required',
                    'data' => [
                        'folders' => [],
                        'files' => []
                    ]
                ], 422);
            }

            return redirect()->back()->with('error', 'Please enter a search term');
        }

        // Search folders
        $folders = Folder::where('workspace_id', $workspaceId)
            ->where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->withCount(['files' => function ($q) {
                $q->where('status', 'active');
            }])
            ->get();

        // Search files
        $files = File::where('workspace_id', $workspaceId)
            ->where('status', 'active')
            ->search($query)
            ->with(['folder', 'uploader'])
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 20);

        if ($request->expectsJson() || $request->ajax()) {
            $foldersData = $folders->map(function ($folder) {
                return [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'description' => $folder->description,
                    'file_count' => $folder->files_count,
                    'created_at' => $folder->created_at->format('d M, Y'),
                ];
            });

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
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'query' => $query,
                    'folders' => $foldersData,
                    'files' => $filesData,
                    'total_folders' => $folders->count(),
                    'total_files' => $files->total(),
                ],
                'pagination' => [
                    'current_page' => $files->currentPage(),
                    'per_page' => $files->perPage(),
                    'total' => $files->total(),
                    'last_page' => $files->lastPage(),
                ]
            ]);
        }

        return view('file-manager.search', compact('folders', 'files', 'query'));
    }
}
