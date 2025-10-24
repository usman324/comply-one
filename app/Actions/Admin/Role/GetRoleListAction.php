<?php

namespace App\Actions\Admin\Role;

use App\Actions\BaseAction;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class GetRoleListAction extends BaseAction
{
    use AsAction;

    protected string $title = 'Role';
    protected string $view = 'admin.role';
    protected string $url = 'roles';
    protected string $permission = 'role';


    public function asController(Request $request)
    {
        if ($request->ajax()) {
            $q_length = $request->length;
            $q_start = $request->start;
            $records_q = Role::where('is_hidden', false);
            $total_records = $records_q->count();
            if ($q_length > 0) {
                $records_q = $records_q->limit($q_start + $q_length);
            }
            $records = $records_q->get();
            return DataTables::of($records)
                ->addColumn('actions', function ($record) {
                    return view($this->view . '.include.actions', [
                        'record' => $record
                    ])->render();
                })
                ->rawColumns(['actions'])
                ->setFilteredRecords($total_records)
                ->setTotalRecords($total_records)
                ->make(true);
        }
        $permissions = Permission::get()
            ->sortBy('category');
        return view($this->view . '.index', get_defined_vars());
    }
}
