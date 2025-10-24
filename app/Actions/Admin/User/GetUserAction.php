<?php

namespace App\Actions\Admin\User;

use App\Actions\BaseAction;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class GetUserAction extends BaseAction
{
    use AsAction;

    protected string $title = 'User';
    protected string $view = 'admin.user';
    protected string $url = 'users';
    protected string $permission = 'user';


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
