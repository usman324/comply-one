<?php

namespace App\Actions\Admin\Workspace\File;

use App\Actions\BaseAction;
use App\Models\File;
use App\Traits\CustomAction;
use App\Traits\RespondsWithJson;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateFileAction extends BaseAction
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
            'display_name' => 'sometimes|required|string|max:255',
            'folder_id' => 'nullable|integer|exists:folders,id',
            'description' => 'nullable|string',
            'tags' => 'nullable|array',
            'is_starred' => 'sometimes|boolean',
        ];
    }

    public function handle($request, int $id)
    {
        $file = File::find($id);

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
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

    public function asController(ActionRequest $request, $id)
    {
        return $this->handle($request, $id);
    }
}
