<?php

namespace App\Actions\Admin\Workspace\File;

use App\Actions\BaseAction;
use App\Models\File;
use App\Traits\CustomAction;
use App\Traits\RespondsWithJson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreFileAction extends BaseAction
{
    use AsAction;
    use RespondsWithJson;
    use CustomAction;

    protected string $title = 'File';
    protected string $view = 'admin.file';
    protected string $url = 'files';
    protected string $permission = 'file';

    public function rules(): array
    {
        return [
            'file' => 'required|file|max:102400', // Max 100MB
            'workspace_id' => 'required|integer|exists:workspaces,id',
            'folder_id' => 'nullable|integer|exists:folders,id',
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'tags' => 'nullable|array',
        ];
    }

    public function handle($request)
    {
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

    public function asController(ActionRequest $request)
    {
        return $this->handle($request);
    }
}
