<?php

namespace App\Actions\Admin\Section;

use App\Actions\BaseAction;
use App\Models\Section;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class GetSectionAction extends BaseAction
{
    use AsAction;

   
    protected string $title = 'Section';
    protected string $view = 'admin.section';
    protected string $url = 'sections';
    protected string $permission = 'section';
    
    public function handle(?int $id = null)
    {
        return  $id ? Section::findOrFail($id) : new Section();
    }

    public function asController(Request $request, $id = null)
    {
        $routeName = $request->route()->getName(); // Get the route name
        $record = $this->handle($id);
         $select_id = $request->select_id;
        return match ($routeName) {
            $this->view . '.create' => view($this->view . '.create', get_defined_vars()),
            $this->view . '.edit' => view($this->view . '.edit', get_defined_vars()),
            $this->view . '.show' => view($this->view . '.show', get_defined_vars()),
        };
    }
}
