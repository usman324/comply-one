<?php

namespace App\Actions\Admin\Workspace\Folder;

use App\Actions\BaseAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetFolderStatisticsAction extends BaseAction
{
    use AsAction;

    protected string $title = 'Folder Statistics';
    protected string $view = 'admin.workspace.file';
    protected string $url = 'folders';
    protected string $permission = 'folder';

    public function asController(Request $request)
    {
        $workspaceId = $request->workspace_id ?? 1;

        $stats = [
            'documents' => [
                'count' => DB::table('files')
                    ->where('workspace_id', $workspaceId)
                    ->where('status', 'active')
                    ->whereIn('mime_type', ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                    ->count(),
                'size' => DB::table('files')
                    ->where('workspace_id', $workspaceId)
                    ->where('status', 'active')
                    ->whereIn('mime_type', ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                    ->sum('file_size'),
            ],
            'media' => [
                'count' => DB::table('files')
                    ->where('workspace_id', $workspaceId)
                    ->where('status', 'active')
                    ->where('mime_type', 'like', 'image%')
                    ->count(),
                'size' => DB::table('files')
                    ->where('workspace_id', $workspaceId)
                    ->where('status', 'active')
                    ->where('mime_type', 'like', 'image%')
                    ->sum('file_size'),
            ],
            'projects' => [
                'count' => DB::table('folders')
                    ->where('workspace_id', $workspaceId)
                    ->where('status', 'active')
                    ->count(),
                'size' => 0,
            ],
            'others' => [
                'count' => DB::table('files')
                    ->where('workspace_id', $workspaceId)
                    ->where('status', 'active')
                    ->whereNotIn('mime_type', ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                    ->where('mime_type', 'not like', 'image%')
                    ->count(),
                'size' => DB::table('files')
                    ->where('workspace_id', $workspaceId)
                    ->where('status', 'active')
                    ->whereNotIn('mime_type', ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                    ->where('mime_type', 'not like', 'image%')
                    ->sum('file_size'),
            ],
        ];

        $totalSize = $stats['documents']['size'] + $stats['media']['size'] + $stats['others']['size'];
        $maxStorage = 119 * 1024 * 1024 * 1024; // 119 GB in bytes

        return response()->json([
            'success' => true,
            'data' => [
                'documents' => [
                    'count' => $stats['documents']['count'],
                    'size' => $this->formatBytes($stats['documents']['size']),
                ],
                'media' => [
                    'count' => $stats['media']['count'],
                    'size' => $this->formatBytes($stats['media']['size']),
                ],
                'projects' => [
                    'count' => $stats['projects']['count'],
                    'size' => $this->formatBytes($stats['projects']['size']),
                ],
                'others' => [
                    'count' => $stats['others']['count'],
                    'size' => $this->formatBytes($stats['others']['size']),
                ],
                'storage' => [
                    'used' => $this->formatBytes($totalSize),
                    'total' => $this->formatBytes($maxStorage),
                    'percentage' => round(($totalSize / $maxStorage) * 100, 2),
                ],
            ]
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
