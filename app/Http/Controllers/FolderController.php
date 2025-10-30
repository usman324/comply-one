<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FolderController extends Controller
{
    /**
     * Display a listing of folders.
     */
    public function index(Request $request)
    {
        $workspaceId = $request->workspace_id ?? 1; // Default workspace
        $parentFolderId = $request->parent_folder_id;

        $folders = Folder::where('workspace_id', $workspaceId)
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

        return response()->json([
            'success' => true,
            'data' => $folders
        ]);
    }

    /**
     * Store a newly created folder.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'workspace_id' => 'required|integer|exists:workspaces,id',
            'parent_folder_id' => 'nullable|integer|exists:folders,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $folder = Folder::create([
                'workspace_id' => $request->workspace_id,
                'parent_folder_id' => $request->parent_folder_id,
                'created_by_user_id' => Auth::id() ?? 1, // Default user
                'name' => $request->name,
                'description' => $request->description,
                'status' => 'active',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Folder created successfully',
                'data' => [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'description' => $folder->description,
                    'parent_folder_id' => $folder->parent_folder_id,
                    'file_count' => 0,
                    'folder_size' => '0 B',
                    'created_at' => $folder->created_at->format('d M, Y'),
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create folder: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified folder.
     */
    public function show($id)
    {
        $folder = Folder::with(['files', 'childFolders'])
            ->withCount('files')
            ->find($id);

        if (!$folder) {
            return response()->json([
                'success' => false,
                'message' => 'Folder not found'
            ], 404);
        }

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

    /**
     * Update the specified folder.
     */
    public function update(Request $request, $id)
    {
        $folder = Folder::find($id);

        if (!$folder) {
            return response()->json([
                'success' => false,
                'message' => 'Folder not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'parent_folder_id' => 'nullable|integer|exists:folders,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Prevent moving folder into itself or its children
        if ($request->has('parent_folder_id') && $request->parent_folder_id) {
            if ($request->parent_folder_id == $id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot move folder into itself'
                ], 422);
            }
        }

        try {
            $folder->update($request->only(['name', 'description', 'parent_folder_id']));

            return response()->json([
                'success' => true,
                'message' => 'Folder updated successfully',
                'data' => [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'description' => $folder->description,
                    'parent_folder_id' => $folder->parent_folder_id,
                    'updated_at' => $folder->updated_at->format('d M, Y'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update folder: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified folder.
     */
    public function destroy($id)
    {
        $folder = Folder::find($id);

        if (!$folder) {
            return response()->json([
                'success' => false,
                'message' => 'Folder not found'
            ], 404);
        }

        try {
            // Soft delete - updates status and deleted_at
            $folder->status = 'deleted';
            $folder->deleted_at = now();
            $folder->save();

            // Also soft delete all files in folder
            $folder->files()->update([
                'status' => 'deleted',
                'deleted_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Folder deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete folder: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get folder statistics.
     */
    public function statistics(Request $request)
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
                'size' => 0, // Calculate based on folder contents
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

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}