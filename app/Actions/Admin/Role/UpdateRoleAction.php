<?php

namespace App\Actions\Admin\Role;

use App\Actions\BaseAction;
use App\Traits\CustomAction;
use Lorisleiva\Actions\ActionRequest;
use App\Traits\RespondsWithJson;
use Exception;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UpdateRoleAction extends BaseAction
{
    use AsAction, RespondsWithJson, CustomAction;

    protected string $title = 'Role';
    protected string $view = 'admin.role';
    protected string $url = 'roles';
    protected string $permission = 'role';

    public function rules(ActionRequest $request): array
    {
        $id = $request->route('id'); // Get the user ID from the route
        return [
            'name' => 'required|unique:roles,name,' . $id
        ];
    }
    public function handle(
        $request,
        int $id
    ) {
        try {
            DB::beginTransaction();
            $data = $request->only('name');
            $role = Role::findOrFail($id);
            $role->update($data);
            $permissions = $request->except('_token', 'name');
            $role->syncPermissions([]);
            foreach ($permissions as $key => $p) {
                $permission = Permission::find($key);
                if ($permission) {
                    $role->givePermissionTo($permission);
                }
            }
            DB::commit();
            return  $this->success('Record Updated Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return  $this->error($e->getMessage());
        }
    }

    public function asController(ActionRequest $request, $id)
    {
        return $this->handle($request, $id);
    }
}
