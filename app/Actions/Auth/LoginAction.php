<?php

namespace App\Actions\Auth;

use App\Exceptions\ApiException;
use App\Models\User;
use App\Traits\CustomAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use App\Traits\RespondsWithJson;
use Lorisleiva\Actions\Concerns\AsAction;

class LoginAction
{
    use AsAction, RespondsWithJson, CustomAction;

    public function rules(): array
    {
        return [
            'email' => ['required'],
            'password' => ['required'],
        ];
    }

    public function handle(
        string $email,
        string $password
    ) {
        $login_type = filter_var($email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $login_type => $email,
            'password' => $password,
        ];

        if (!Auth::attempt($credentials)) {
            // throw  new ApiException(__('Email or password invalid'));
            return $this->error('Email or password invalid');
        }
        saveActivity([
            'title' => 'User Logged In',
            'type' => 'auth',
            'ip' => request()->ip(),
        ]);
        return $this->success('logged in Successfully', url('/'));
        // return ['success' => true, 'message' => 'logged in Successfully', 'redirect_url' => url('/')];
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request->email, $request->password);
    }
}
