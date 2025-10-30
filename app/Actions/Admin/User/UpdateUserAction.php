<?php

namespace App\Actions\Admin\User;

use App\Actions\BaseAction;
use App\Models\User;
use App\Traits\CustomAction;
use Lorisleiva\Actions\ActionRequest;
use App\Traits\RespondsWithJson;
use Exception;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateUserAction extends BaseAction
{
    use AsAction;
    use RespondsWithJson;
    use CustomAction;

    protected string $title = 'User';
    protected string $view = 'admin/user';
    protected string $url = 'users';
    protected string $permission = 'user';

    public function rules(ActionRequest $request): array
    {
        $id = $request->route('id'); // Get the user ID from the route

        return [
            // 'first_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => ['nullable', 'confirmed'],
        ];
    }
    public function handle(
        $request,
        int $id
    ) {
        try {
            DB::beginTransaction();
            $record = User::findOrFail($id);
            $image = $request->avatar;
            $image_name = '';
            if ($image) {
                deleteImage('user/' . $record->avatar, $record->avatar);
                $name = rand(10, 100) . time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('user', $name);
                $image_name = $name;
            }
            $record->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'workspace_id' => $request->workspace_id,
                'address' => $request->address,
                'phone' => $request->phone,
                'password' => $request->email,
                'email' => $request->email,
                'city' => $request->city,
                'status' => $request->status,
                'password' => $request->password ? bcrypt($request->password) : $record->password,
                'avatar' => $image_name ? $image_name : $record->avatar,
            ]);
            if ($request->role_id) {
                if ($record->getRoleNames()->first()) {
                    $record?->removeRole($record->getRoleNames()->first());
                }
                $record->assignRole($request->role_id);
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
