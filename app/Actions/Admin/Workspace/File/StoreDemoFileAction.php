<?php

namespace App\Actions\Admin\Workspace\File;

use App\Actions\BaseAction;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;

class StoreDemoFileAction extends BaseAction
{
    protected string $title = 'File';
    protected string $view = 'admin.file';
    protected string $url = 'files';
    protected string $permission = 'file';

    public function rules(): array
    {
        return [
            'workspace_id' => 'required|integer',
            'folder_id' => 'nullable|integer|exists:folders,id',
            'display_name' => 'required|string|max:255',
            'file_type' => 'required|string',
            'file_size' => 'required|numeric',
            'description' => 'nullable|string',
        ];
    }

    public function handle($request)
    {
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

    public function asController(ActionRequest $request)
    {
        return $this->handle($request);
    }
}
