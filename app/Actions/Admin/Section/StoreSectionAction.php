<?php

namespace App\Actions\Admin\Section;

use App\Actions\BaseAction;
use App\Models\Section;
use App\Traits\CustomAction;
use Lorisleiva\Actions\ActionRequest;
use App\Traits\RespondsWithJson;
use Exception;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreSectionAction extends BaseAction
{
    use AsAction;
    use RespondsWithJson;
    use CustomAction;

    protected string $title = 'Section';
    protected string $view = 'admin.section';
    protected string $url = 'sections';
    protected string $permission = 'section';


    public function rules(): array
    {
        return [
            'name' => 'required',
        ];
    }
    public function handle(
        $request,
    ) {
        try {
            $record = Section::create([
                'name' => $request->name,
            ]);
            return  $this->success('Record Added Successfully');
        } catch (Exception $e) {
            return  $this->error($e->getMessage());
        }
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request);
    }
}
