<?php

namespace App\Actions\Admin\Workspace\Folder;

use App\Actions\BaseAction;
use App\Models\Folder;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class GetFolderAction extends BaseAction
{
    use AsAction;

    protected string $title = 'Folder';
    protected string $view = 'admin.workspace.file';
    protected string $url = 'folders';
    protected string $permission = 'folder';

    public function handle(?int $id = null): Folder
    {
        return $id ? Folder::findOrFail($id) : new Folder();
    }

    public function asController(Request $request, $id = null)
    {
        $folder = $this->handle($id);
        $routeName = $request->route()->getName();

        if ($request->expectsJson() || $request->ajax()) {
            if (!$folder->exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Folder not found'
                ], 404);
            }

            $folder->load(['files', 'childFolders'])
                ->loadCount('files');

            $folderData = [
                'id' => $folder->id,
                'name' => $folder->name,
                'description' => $folder->description,
                'parent_folder_id' => $folder->parent_folder_id,
                'file_count' => $folder->files_count,
                'folder_size' => $this->formatBytes($folder->files->sum('file_size')),
                'breadcrumb' => $folder->getBreadcrumb(),
            ];

            return response()->json([
                'success' => true,
                'data' => $folderData
            ]);
        }

        $viewName = match ($routeName) {
            "{$this->view}.create" => "{$this->view}.create",
            "{$this->view}.edit"   => "{$this->view}.edit",
            default                => "{$this->view}.show",
        };

        return view($viewName, compact('folder'));
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
