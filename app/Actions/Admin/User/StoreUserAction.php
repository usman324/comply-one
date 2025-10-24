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

class StoreUserAction extends BaseAction
{
    use AsAction, RespondsWithJson, CustomAction;

    protected string $title = 'User';
    protected string $view = 'admin/user';
    protected string $url = 'users';
    protected string $permission = 'user';

    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed'],
        ];
    }
    public function handle(
        $request,
    ) {
        try {
            DB::beginTransaction();
            $image = $request->avatar;
            $image_name = '';
            if ($image) {
                $name = rand(10, 100) . time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('user', $name);
                $image_name = $name;
            }
            $user_number = generateUUID(4);
            $record = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'address' => $request->address,
                'phone' => $request->phone,
                'password' => $request->email,
                'email' => $request->email,
                'city' => $request->city,
                'status' => 'active',
                'password' => bcrypt($request->password),
                'avatar' => $image_name,
            ]);
            $record->update([
                'user_number' => $user_number . $record->id
            ]);
            if ($request->role_id) {
                $record->assignRole($request->role_id);
            }
            MetaUserAction::run($request,$record);
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
