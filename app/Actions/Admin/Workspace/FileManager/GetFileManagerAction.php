<?php

namespace App\Actions\Admin\Workspace\FileManager;

use App\Actions\BaseAction;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class GetFileManagerAction extends BaseAction
{
    use AsAction;

    protected string $title = 'File Manager';
    protected string $view = 'admin.file';
    protected string $url = 'file-managers';
    protected string $permission = 'file-manager';

    public function asController(Request $request)
    {
        $workspaceId = $request->workspace_id ?? 1;
        $folderId = $request->folder_id;
        $filter = $request->filter;
        $search = $request->search;

        // Get folders - currently empty array as per original code
        $folders = [];

        // Get files
        $filesQuery = File::where('workspace_id', $workspaceId)
            ->with(['folder', 'uploader']);

        // Apply filters
        if ($folderId) {
            $filesQuery->where('folder_id', $folderId);
        }

        if ($filter) {
            switch ($filter) {
                case 'documents':
                    $filesQuery->ofType('documents');
                    break;
                case 'media':
                case 'images':
                    $filesQuery->ofType('images');
                    break;
                case 'video':
                    $filesQuery->ofType('video');
                    break;
                case 'music':
                    $filesQuery->ofType('music');
                    break;
                case 'recent':
                    $filesQuery->recent(7);
                    break;
                case 'starred':
                    $filesQuery->starred();
                    break;
                case 'deleted':
                    $filesQuery->where('status', 'deleted');
                    break;
                default:
                    $filesQuery->where('status', 'active');
            }
        } else {
            $filesQuery->where('status', 'active');
        }

        if ($search) {
            $filesQuery->search($search);
        }

        $files = $filesQuery->orderBy('created_at', 'desc')->paginate(20);

        // Get root folders for sidebar
        $rootFolders = Folder::where('workspace_id', $workspaceId)
            ->whereNull('parent_folder_id')
            ->where('status', 'active')
            ->get();

        // Get all folders for dropdown
        $allFolders = Folder::where('workspace_id', $workspaceId)
            ->where('status', 'active')
            ->get();

        // Get statistics
        $statistics = $this->getStatistics($workspaceId);

        // Storage info
        $storageInfo = $this->getStorageInfo($workspaceId);

        return view('admin.file.index', compact(
            'folders',
            'files',
            'rootFolders',
            'allFolders',
            'statistics',
            'storageInfo'
        ) + $storageInfo);
    }

    private function getStatistics($workspaceId)
    {
        $documents = File::where('workspace_id', $workspaceId)
            ->where('status', 'active')
            ->ofType('documents')
            ->selectRaw('COUNT(*) as count, SUM(file_size) as size')
            ->first();

        $media = File::where('workspace_id', $workspaceId)
            ->where('status', 'active')
            ->ofType('images')
            ->selectRaw('COUNT(*) as count, SUM(file_size) as size')
            ->first();

        $projects = Folder::where('workspace_id', $workspaceId)
            ->where('status', 'active')
            ->count();

        $others = File::where('workspace_id', $workspaceId)
            ->where('status', 'active')
            ->whereNotIn('mime_type', [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ])
            ->where('mime_type', 'not like', 'image%')
            ->selectRaw('COUNT(*) as count, SUM(file_size) as size')
            ->first();

        return [
            'documents' => [
                'name' => 'Documents',
                'count' => $documents->count ?? 0,
                'size' => formatBytes($documents->size ?? 0),
                'icon' => 'ri-file-text-line',
                'color' => 'secondary'
            ],
            'media' => [
                'name' => 'Media',
                'count' => $media->count ?? 0,
                'size' => formatBytes($media->size ?? 0),
                'icon' => 'ri-gallery-line',
                'color' => 'success'
            ],
            'projects' => [
                'name' => 'Projects',
                'count' => $projects,
                'size' => formatBytes(0),
                'icon' => 'ri-folder-2-line',
                'color' => 'warning'
            ],
            'others' => [
                'name' => 'Others',
                'count' => $others->count ?? 0,
                'size' => formatBytes($others->size ?? 0),
                'icon' => 'ri-error-warning-line',
                'color' => 'primary'
            ]
        ];
    }

    private function getStorageInfo($workspaceId)
    {
        $totalSize = File::where('workspace_id', $workspaceId)
            ->where('status', 'active')
            ->sum('file_size');

        $maxStorage = 119 * 1024 * 1024 * 1024; // 119 GB in bytes
        $percentage = ($totalSize / $maxStorage) * 100;

        return [
            'usedStorage' => formatBytes($totalSize),
            'totalStorage' => '119 GB',
            'storagePercentage' => round($percentage, 2)
        ];
    }
}
