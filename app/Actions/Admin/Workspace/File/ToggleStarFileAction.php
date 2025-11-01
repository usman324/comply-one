<?php

namespace App\Actions\Admin\Workspace\File;

use App\Actions\BaseAction;
use App\Models\File;
use App\Traits\CustomAction;
use App\Traits\RespondsWithJson;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ToggleStarFileAction extends BaseAction
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

    public function asController(ActionRequest $request, $id)
    {
        return $this->handle($id);
    }
}
