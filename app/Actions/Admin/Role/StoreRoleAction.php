<?php

namespace App\Actions\Admin\Role;

use App\Actions\BaseAction;
use App\Traits\CustomAction;
use Illuminate\Support\Facades\Auth;
use App\Traits\RespondsWithJson;
use Exception;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class StoreRoleAction extends BaseAction
{
    use AsAction, RespondsWithJson, CustomAction;

    protected string $title = 'Role';
    protected string $view = 'admin.role';
    protected string $url = 'roles';
    protected string $permission = 'role';

    public function rules(): array
    {
        return [
            'name' => 'required|unique:roles,name'
        ];
    }
    public function handle(
        $request,
    ) {
        try {
            DB::beginTransaction();
            $data = $request->only('name');
            $data['guard_name'] = 'web';
            $role = Role::create($data);
            $permissions = $request->except('_token', 'name');
            foreach ($permissions as $key => $p) {
                $permission = Permission::find($key);
                if ($permission) {
                    $role->givePermissionTo($permission);
                }
            }
            DB::commit();
            return  $this->success('Record Added Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return  $this->error($e->getMessage());
        }
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request);
    }
}
