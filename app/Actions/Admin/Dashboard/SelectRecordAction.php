<?php

namespace App\Actions\Admin\Dashboard;

use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class SelectRecordAction
{
    use AsAction;
    public const VIEW = 'admin';
    public const URL = '/top-customers';

    public function __construct()
    {
        view()->share([
            'url' => url(self::URL),
        ]);
    }


    public function asController(Request $request)
    {
        $model = $request->model;
        $fullClass = 'App\\Models\\' . $model;
        // if (!class_exists($fullClass)) {
        //     abort(404, 'Model not found');
        // }
        $records = $fullClass::latest()->get();
        return view(self::VIEW . '.dashboard.include.select', get_defined_vars());
    }
}
