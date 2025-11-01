<?php

namespace App\Actions\Admin\Workspace\File;

use App\Actions\BaseAction;
use App\Models\File;
use App\Traits\CustomAction;
use App\Traits\RespondsWithJson;
use Exception;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ForceDeleteFileAction extends BaseAction
{
    use AsAction;
    use RespondsWithJson;
    use CustomAction;

    protected string $title = 'File';
    protected string $view = 'admin.file';
    protected string $url = 'files';
    protected string $permission = 'file';

    public function handle(int $id)
    {
        try {
            $file = File::find($id);

            if (!$file) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found'
                ], 404);
            }

            // Delete physical file
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }

            // Delete database record
            $file->forceDelete();

            return $this->success('File permanently deleted');
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function asController(ActionRequest $request, $id)
    {
        return $this->handle($id);
    }
}
