<?php

namespace App\Actions\Admin\Workspace\File;

use App\Actions\BaseAction;
use App\Models\File;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GetFileListAction extends BaseAction
{
    use AsAction;

    protected string $title = 'File';
    protected string $view = 'admin.workspace.file';
    protected string $url = 'files';
    protected string $permission = 'file';

    public function asController(Request $request)
    {
        $workspaceId = $request->workspace_id ?? 1;
        $folderId = $request->folder_id;
        $filter = $request->filter; // recent, important, deleted
        $fileType = $request->file_type; // documents, images, video, music
        $search = $request->search;

        $query = File::where('workspace_id', $workspaceId);

        // Apply filters
        if ($folderId) {
            $query->where('folder_id', $folderId);
        }

        if ($filter === 'recent') {
            $query->recent(7);
        } elseif ($filter === 'important') {
            $query->starred();
        } elseif ($filter === 'deleted') {
            $query->where('status', 'deleted');
        } else {
            $query->where('status', 'active');
        }

        if ($fileType && $fileType !== 'all') {
            $query->ofType($fileType);
        }

        if ($search) {
            $query->search($search);
        }

        if ($request->ajax()) {
            return DataTables::eloquent($query->with(['folder', 'uploader']))
                ->addIndexColumn()
                ->addColumn('actions', function ($record) {
                    return view('admin.workspace.file.include.actions', [
                        'record' => $record,
                        'url' => $this->url,
                        'permission' => $this->permission
                    ])->render();
                })
                ->addColumn('original_name', function ($record) {
                    return $record->original_name;
                })
                ->addColumn('display_name', function ($record) {
                    return $record->display_name;
                })
                ->addColumn('file_size', function ($record) {
                    return $record->formatted_size;
                })
                ->addColumn('folder_name', function ($record) {
                    return $record->folder ? $record->folder->name : 'Root';
                })
                ->addColumn('uploaded_by', function ($record) {
                    return $record->uploader ? $record->uploader->name : 'Unknown';
                })
                ->addColumn('is_starred', function ($record) {
                    $badge = $record->is_starred ? 'warning' : 'secondary';
                    $icon = $record->is_starred ? 'ri-star-fill' : 'ri-star-line';
                    return '<i class="' . $icon . ' text-' . $badge . '"></i>';
                })
                ->addColumn('status', function ($record) {
                    $badge = $record->status === 'active' ? 'success' : 'secondary';
                    return '<span class="badge bg-' . $badge . '">' . ucfirst($record->status) . '</span>';
                })
                ->addColumn('created_at', function ($record) {
                    return $record->created_at->format('d M, Y');
                })
                ->rawColumns(['actions', 'is_starred', 'status'])
                ->make(true);
        }

        $files = $query->with(['folder', 'uploader'])
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 20);

        $filesData = $files->map(function ($file) {
            return [
                'id' => $file->id,
                'original_name' => $file->original_name,
                'display_name' => $file->display_name,
                'extension' => $file->extension,
                'file_size' => $file->formatted_size,
                'file_size_bytes' => $file->file_size,
                'mime_type' => $file->mime_type,
                'file_icon' => $file->file_icon,
                'folder_id' => $file->folder_id,
                'folder_name' => $file->folder ? $file->folder->name : 'Root',
                'is_starred' => $file->is_starred,
                'description' => $file->description,
                'tags' => $file->tags,
                'status' => $file->status,
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
}
