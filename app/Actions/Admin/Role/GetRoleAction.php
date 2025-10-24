<?php

namespace App\Actions\Admin\Role;

use App\Actions\BaseAction;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class GetRoleAction extends BaseAction
{
    use AsAction;

    protected string $title = 'Role';
    protected string $view = 'admin.role';
    protected string $url = 'roles';
    protected string $permission = 'role';


    public function handle(?int $id = null)
    {
        return  $id ? Role::findOrFail($id) : new Role();
    }

    public function asController(Request $request, $id = null)
    {
        $routeName = $request->route()->getName(); // Get the route name
        $record = $this->handle($id);
        $permissions = Permission::orderby('category')->get()
            ->sortBy('category');
        return match ($routeName) {
            $this->view . '.create' => view($this->view . '.create', get_defined_vars()),
            $this->view . '.edit' => view($this->view . '.edit', get_defined_vars()),
            $this->view . '.show' => view($this->view . '.show', get_defined_vars()),
        };
    }
}
