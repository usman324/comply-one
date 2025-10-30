<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FileManagerController extends Controller
{
    /**
     * Display the main file manager dashboard
     */
    public function index(Request $request)
    {
        $workspaceId = $request->workspace_id ?? 1;
        $folderId = $request->folder_id;
        $filter = $request->filter;
        $search = $request->search;

        // Get folders
        $foldersQuery = Folder::where('workspace_id', $workspaceId)
            ->where('status', 'active')
            ->withCount(['files' => function ($query) {
                $query->where('status', 'active');
            }])
            ->with(['files' => function ($query) {
                $query->where('status', 'active');
            }]);
            $folders=[];
        // if ($folderId) {
        //     $foldersQuery->where('parent_folder_id', $folderId);
        // } else {
        //     $foldersQuery->whereNull('parent_folder_id');
        // }

        // $folders = $foldersQuery->get()->map(function ($folder) {
        //     dd($folder);
        //     $folder->folder_size = $folder->files->sum('file_size');
        //     return $folder;
        // });

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

    /**
     * Get statistics
     */
    public function statistics(Request $request)
    {
        $workspaceId = $request->workspace_id ?? 1;
        $statistics = $this->getStatistics($workspaceId);

        return view('file-manager.statistics', compact('statistics'));
    }

    /**
     * Show trash (deleted items)
     */
    public function trash(Request $request)
    {
        $workspaceId = $request->workspace_id ?? 1;

        $deletedFolders = Folder::where('workspace_id', $workspaceId)
            ->where('status', 'deleted')
            ->withTrashed()
            ->get();

        $deletedFiles = File::where('workspace_id', $workspaceId)
            ->where('status', 'deleted')
            ->withTrashed()
            ->paginate(20);

        return view('file-manager.trash', compact('deletedFolders', 'deletedFiles'));
    }

    /**
     * Show recent files
     */
    public function recent(Request $request)
    {
        $workspaceId = $request->workspace_id ?? 1;

        $files = File::where('workspace_id', $workspaceId)
            ->where('status', 'active')
            ->recent(30)
            ->with(['folder', 'uploader'])
            ->paginate(20);

        return view('file-manager.recent', compact('files'));
    }

    /**
     * Show starred files
     */
    public function starred(Request $request)
    {
        $workspaceId = $request->workspace_id ?? 1;

        $files = File::where('workspace_id', $workspaceId)
            ->where('status', 'active')
            ->starred()
            ->with(['folder', 'uploader'])
            ->paginate(20);

        return view('file-manager.starred', compact('files'));
    }

    /**
     * Search files and folders
     */
    public function search(Request $request)
    {
        $workspaceId = $request->workspace_id ?? 1;
        $query = $request->query('q');

        $folders = Folder::where('workspace_id', $workspaceId)
            ->where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->get();

        $files = File::where('workspace_id', $workspaceId)
            ->where('status', 'active')
            ->search($query)
            ->with(['folder', 'uploader'])
            ->paginate(20);

        return view('file-manager.search', compact('folders', 'files', 'query'));
    }

    /**
     * Get statistics data
     */
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

    /**
     * Get storage information
     */
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
