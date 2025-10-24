<?php

namespace App\Actions\Admin\Profile;

use App\Actions\BaseAction;
use App\Models\User;
use App\Traits\CustomAction;
use Lorisleiva\Actions\ActionRequest;
use App\Traits\RespondsWithJson;
use Exception;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateProfileAction extends BaseAction
{
    use AsAction, RespondsWithJson, CustomAction;

    public function handle(
        $request,
        int $id
    ) {
        try {
            $user = User::findOrFail($id);
            $image = $request->avatar;
            $image_name = '';
            if ($image) {
                deleteImage('user/' . $user->avatar, $user->avatar);
                $name = rand(10, 100) . time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('user', $name);
                $image_name = $name;
            }
            $user->update([
                'name' =>  $request->name,
                'phone' =>  $request->phone,
                'avatar' => $image_name ? $image_name : $user->avatar,
            ]);
            return  $this->success('Profile Updated Successfully');
        } catch (Exception $e) {
            return  $this->error($e->getMessage());
        }
    }

    public function asController(ActionRequest $request, $id)
    {
        return $this->handle($request, $id);
    }
}
