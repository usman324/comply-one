<?php

namespace App\Actions\Admin\Workspace\Folder;

use App\Actions\BaseAction;
use App\Models\Workspace;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GetFolderListAction extends BaseAction
{
    use AsAction;

    protected string $title = 'Folder';
    protected string $view = 'admin.workspace.file';
    protected string $url = 'folders';
    protected string $permission = 'folder';

    public function asController(Request $request, Workspace $workspace)
    {
        $parentFolderId = $request->parent_folder_id;

        $folders =  $workspace->folders()
            ->where('status', 'active')
            ->when($parentFolderId, function ($query) use ($parentFolderId) {
                return $query->where('parent_folder_id', $parentFolderId);
            }, function ($query) {
                return $query->whereNull('parent_folder_id');
            })
            ->with(['files' => function ($query) {
                $query->where('status', 'active');
            }])
            ->withCount(['files as file_count' => function ($query) {
                $query->where('status', 'active');
            }])
            ->get()
            ->map(function ($folder) {
                return [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'description' => $folder->description,
                    'parent_folder_id' => $folder->parent_folder_id,
                    'file_count' => $folder->file_count,
                    'folder_size' => $this->formatBytes($folder->files->sum('file_size')),
                    'created_at' => $folder->created_at->format('d M, Y'),
                    'updated_at' => $folder->updated_at->format('d M, Y'),
                ];
            });

        if ($request->ajax() && $request->has('datatable')) {
            return DataTables::of($folders)
                ->addIndexColumn()
                ->addColumn('actions', function ($record) {
                    return view('admin.folder.include.actions', [
                        'record' => (object)$record,
                        'url' => $this->url,
                        'permission' => $this->permission
                    ])->render();
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return response()->json([
            'success' => true,
            'data' => $folders
        ]);
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
