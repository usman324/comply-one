<?php

namespace App\Actions\Admin\Workspace;

use App\Actions\BaseAction;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class GetWorkspaceAction extends BaseAction
{
    use AsAction;

    protected string $title = 'Workspace';
    protected string $view = 'admin.workspace';
    protected string $url = 'workspaces';
    protected string $permission = 'workspace';


    public function handle(?int $id = null)
    {
        return  $id ? User::findOrFail($id) : new User();
    }

    public function asController(Request $request, $id = null)
    {
        $routeName = $request->route()->getName(); // Get the route name
        $record = $this->handle($id);
        $roles = Role::where('is_hidden', false)->get();
        
        return match ($routeName) {
            $this->view . '.create' => view($this->view . '.create', get_defined_vars()),
            $this->view . '.edit' => view($this->view . '.edit', get_defined_vars()),
            $this->view . '.show' => view($this->view . '.show', get_defined_vars()),
        };
    }
}
