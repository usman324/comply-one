<?php

namespace App\Actions\Admin\Profile;

use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;


class GetProfileAction 
{
    use AsAction;
    const VIEW = 'admin';
    const URL = '/top-customers';

    public function __construct()
    {
        view()->share([
            'url' => url(self::URL),
        ]);
    }
    public function middleware()
    {
        return ['auth'];
    }

    public function asController(Request $request)
    {
        return view(self::VIEW . '.dashboard.profile', get_defined_vars());
    }
}
