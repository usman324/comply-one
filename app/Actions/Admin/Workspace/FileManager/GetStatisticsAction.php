<?php

namespace App\Actions\Admin\Workspace\FileManager;

use App\Actions\BaseAction;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class GetStatisticsAction extends BaseAction
{
    use AsAction;

    protected string $title = 'File Manager Statistics';
    protected string $view = 'admin.file-manager';
    protected string $url = 'file-managers';
    protected string $permission = 'file-manager';

    public function asController(Request $request)
    {
        $workspaceId = $request->workspace_id ?? 1;

        $statistics = $this->getStatistics($workspaceId);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);
        }

        return view('file-manager.statistics', compact('statistics'));
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
}
