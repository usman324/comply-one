<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class GetLoginAction
{
    use AsAction;

    public function asController(Request $request)
    {
        return view('auth.login');
    }
}
