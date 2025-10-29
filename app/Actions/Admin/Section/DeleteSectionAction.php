<?php

namespace App\Actions\Admin\Section;

use App\Actions\BaseAction;
use App\Models\Section;
use App\Traits\CustomAction;
use Lorisleiva\Actions\ActionRequest;
use App\Traits\RespondsWithJson;
use Exception;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteSectionAction extends BaseAction
{
    use AsAction, RespondsWithJson, CustomAction;


    protected string $title = 'Section';
    protected string $view = 'admin.section';
    protected string $url = 'sections';
    protected string $permission = 'section';

    public function handle(
        int $id
    ) {
        try {
            $record = Section::findOrFail($id);
            $record->delete();
            return $this->success('Record Deleted Successfully');
        } catch (Exception $e) {
            return  $this->error($e->getMessage());
        }
    }

    public function asController(ActionRequest $request, $id)
    {
        return $this->handle($id);
    }
}
