<?php

namespace App\Actions\Admin\Dashboard;

use App\Models\User;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class DashboardAction
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
    public function middleware()
    {
        return ['auth'];
    }

    public function asController(Request $request)
    {
        if ($request->dashbaord_meta) {
            return $this->getDashboardMetas($request);
        }
        return view(self::VIEW . '.dashboard.index', get_defined_vars());
    }

    public function getDashboardMetas(Request $request)
    {

        $users = User::whereRoleNot(['admin', 'employee'])->count();
        $employees = User::whereRole('employee')->count();
        return [
            'total_user' => $users,
            'total_employees' => $employees,

        ];
    }
}
