<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\ActionRequest;
use App\Traits\RespondsWithJson;
use Lorisleiva\Actions\Concerns\AsAction;

class LogoutAction
{
    use AsAction;
    use RespondsWithJson;

    public function handle()
    {
        saveActivity([
            'title' => 'User Logged Out',
            'type' => 'auth',
            'ip' => request()->ip(),
        ]);
        Auth::logout();

        return ['success' => true, 'message' => 'Logout  Successfully'];
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle();
    }

    public function htmlResponse(array $response)
    {
        return redirect('/');
    }

    public function jsonResponse(array $response)
    {
        return $this->response($response);
    }
    private function response(array $response)
    {
        return RespondsWithJson::getDefaultResponse($response);
    }
}
