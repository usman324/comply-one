<?php

namespace App\Actions\Admin\Role;

use App\Actions\BaseAction;
use App\Traits\CustomAction;
use Lorisleiva\Actions\ActionRequest;
use App\Traits\RespondsWithJson;
use Exception;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\Permission\Models\Role;

class DeleteRoleAction extends BaseAction
{
    use AsAction, RespondsWithJson, CustomAction;

    protected string $title = 'Role';
    protected string $view = 'admin.role';
    protected string $url = 'roles';
    protected string $permission = 'role';

    public function handle(
        int $id
    ) {
        try {
            $user = Role::findOrFail($id);
            $user->delete();
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
