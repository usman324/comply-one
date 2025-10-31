<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FileController extends Controller
{
    /**
     * Display a listing of files.
     */
    public function index(Request $request)
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

    /**
     * Store a newly uploaded file.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:102400', // Max 100MB
            'workspace_id' => 'required|integer|exists:workspaces,id',
            'folder_id' => 'nullable|integer|exists:folders,id',
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'tags' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $uploadedFile = $request->file('file');
            $originalName = $uploadedFile->getClientOriginalName();
            $extension = $uploadedFile->getClientOriginalExtension();
            $mimeType = $uploadedFile->getMimeType();
            $fileSize = $uploadedFile->getSize();

            // Generate unique filename
            $fileName = Str::uuid() . '.' . $extension;

            // Store file
            $filePath = $uploadedFile->storeAs('uploads', $fileName, 'public');

            // Create file record
            $file = File::create([
                'workspace_id' => $request->workspace_id,
                'folder_id' => $request->folder_id,
                'uploaded_by_user_id' => Auth::id() ?? 1,
                'original_name' => $originalName,
                'display_name' => $request->display_name ?? $originalName,
                'file_path' => $filePath,
                'file_size' => $fileSize,
                'mime_type' => $mimeType,
                'extension' => $extension,
                'description' => $request->description,
                'tags' => $request->tags,
                'status' => 'active',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'data' => [
                    'id' => $file->id,
                    'original_name' => $file->original_name,
                    'display_name' => $file->display_name,
                    'file_size' => $file->formatted_size,
                    'extension' => $file->extension,
                    'file_icon' => $file->file_icon,
                    'created_at' => $file->created_at->format('d M, Y'),
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store file without actual upload (for demo purposes).
     */
    public function storeDemo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'workspace_id' => 'required|integer',
            'folder_id' => 'nullable|integer|exists:folders,id',
            'display_name' => 'required|string|max:255',
            'file_type' => 'required|string',
            'file_size' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Map file types to extensions and mime types
            $typeMapping = [
                'documents' => ['extension' => 'pdf', 'mime_type' => 'application/pdf'],
                'images' => ['extension' => 'jpg', 'mime_type' => 'image/jpeg'],
                'video' => ['extension' => 'mp4', 'mime_type' => 'video/mp4'],
                'music' => ['extension' => 'mp3', 'mime_type' => 'audio/mpeg'],
            ];

            $mapping = $typeMapping[$request->file_type] ?? ['extension' => 'txt', 'mime_type' => 'text/plain'];
            $fileName = $request->display_name . '.' . $mapping['extension'];
            $fileSizeBytes = $request->file_size * 1024 * 1024; // Convert MB to bytes

            $file = File::create([
                'workspace_id' => $request->workspace_id,
                'folder_id' => $request->folder_id,
                'uploaded_by_user_id' => Auth::id() ?? 1,
                'original_name' => $fileName,
                'display_name' => $request->display_name,
                'file_path' => 'uploads/' . Str::uuid() . '.' . $mapping['extension'],
                'file_size' => $fileSizeBytes,
                'mime_type' => $mapping['mime_type'],
                'extension' => $mapping['extension'],
                'description' => $request->description,
                'status' => 'active',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File created successfully',
                'data' => [
                    'id' => $file->id,
                    'original_name' => $file->original_name,
                    'display_name' => $file->display_name,
                    'file_size' => $file->formatted_size,
                    'extension' => $file->extension,
                    'file_icon' => $file->file_icon,
                    'folder_id' => $file->folder_id,
                    'created_at' => $file->created_at->format('d M, Y'),
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified file.
     */
    public function show($id)
    {
        $file = File::with(['folder', 'uploader'])->find($id);

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        $fileData = [
            'id' => $file->id,
            'original_name' => $file->original_name,
            'display_name' => $file->display_name,
            'file_path' => $file->file_path,
            'file_size' => $file->formatted_size,
            'file_size_bytes' => $file->file_size,
            'mime_type' => $file->mime_type,
            'extension' => $file->extension,
            'file_icon' => $file->file_icon,
            'folder_id' => $file->folder_id,
            'folder_name' => $file->folder ? $file->folder->name : 'Root',
            'is_starred' => $file->is_starred,
            'description' => $file->description,
            'tags' => $file->tags,
            'status' => $file->status,
            'uploaded_by' => $file->uploader ? $file->uploader->name : 'Unknown',
            'created_at' => $file->created_at->format('d M, Y H:i'),
            'updated_at' => $file->updated_at->format('d M, Y H:i'),
        ];

        return response()->json([
            'success' => true,
            'data' => $fileData
        ]);
    }

    /**
     * Update the specified file.
     */
    public function update(Request $request, $id)
    {
        $file = File::find($id);

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'display_name' => 'sometimes|required|string|max:255',
            'folder_id' => 'nullable|integer|exists:folders,id',
            'description' => 'nullable|string',
            'tags' => 'nullable|array',
            'is_starred' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file->update($request->only([
                'display_name',
                'folder_id',
                'description',
                'tags',
                'is_starred'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'File updated successfully',
                'data' => [
                    'id' => $file->id,
                    'display_name' => $file->display_name,
                    'folder_id' => $file->folder_id,
                    'is_starred' => $file->is_starred,
                    'description' => $file->description,
                    'tags' => $file->tags,
                    'updated_at' => $file->updated_at->format('d M, Y'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified file.
     */
    public function destroy($id)
    {
        $file = File::find($id);

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        try {
            // Soft delete
            $file->status = 'deleted';
            $file->deleted_at = now();
            $file->save();

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Permanently delete file.
     */
    public function forceDestroy($id)
    {
        $file = File::find($id);

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        try {
            // Delete physical file
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }

            // Delete database record
            $file->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'File permanently deleted'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download file.
     */
    public function download($id)
    {
        $file = File::find($id);

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        if (!Storage::disk('public')->exists($file->file_path)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found on disk'
            ], 404);
        }

        return Storage::disk('public')->download($file->file_path, $file->original_name);
    }

    /**
     * Toggle star status.
     */
    public function toggleStar($id)
    {
        $file = File::find($id);

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        $file->is_starred = !$file->is_starred;
        $file->save();

        return response()->json([
            'success' => true,
            'message' => $file->is_starred ? 'File starred' : 'File unstarred',
            'data' => [
                'id' => $file->id,
                'is_starred' => $file->is_starred,
            ]
        ]);
    }
}
