<?php

namespace App\Actions\Admin\User;

use App\Actions\BaseAction;
use App\Models\User;
use App\Traits\CustomAction;
use Lorisleiva\Actions\ActionRequest;
use App\Traits\RespondsWithJson;
use Exception;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteUserAction extends BaseAction
{
    use AsAction;
    use RespondsWithJson;
    use CustomAction;

    protected string $title = 'User';
    protected string $view = 'admin.user';
    protected string $url = 'users';
    protected string $permission = 'user';

    public function handle(
        int $id
    ) {
        try {
            $user = User::findOrFail($id);
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
