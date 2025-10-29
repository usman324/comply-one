<?php

namespace App\Actions\Admin\Section;

use App\Actions\BaseAction;
use App\Models\Section;
use App\Traits\CustomAction;
use Lorisleiva\Actions\ActionRequest;
use App\Traits\RespondsWithJson;
use Exception;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateSectionAction extends BaseAction
{
    use AsAction;
    use RespondsWithJson;
    use CustomAction;

    protected string $title = 'Section';
    protected string $view = 'admin.section';
    protected string $url = 'sections';
    protected string $permission = 'section';

    public function rules(ActionRequest $request): array
    {

        return [
            'name' => 'required',
        ];
    }
    public function handle(
        $request,
        int $id
    ) {
        try {
            $record = Section::findOrFail($id);
            $record->update([
                'name' => $request->name,
            ]);
            return  $this->success('Record Updated Successfully');
        } catch (Exception $e) {
            return  $this->error($e->getMessage());
        }
    }

    public function asController(ActionRequest $request, $id)
    {
        return $this->handle($request, $id);
    }
}
