<?php

namespace App\Actions\Admin\Workspace\File;

use App\Actions\BaseAction;
use App\Models\File;
use App\Traits\CustomAction;
use App\Traits\RespondsWithJson;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class DownloadFileAction extends BaseAction
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

    public function asController(ActionRequest $request, $id)
    {
        return $this->handle($id);
    }
}
