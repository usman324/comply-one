<?php

namespace App\Actions\Admin\Workspace;

use App\Actions\BaseAction;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserActivity;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class ActivityWorkspaceListAction extends BaseAction
{
    use AsAction;

    protected string $title = 'User History';
    protected string $view = 'admin.user-activity';
    protected string $url = 'user-activities';
    protected string $permission = 'user_history';


    public function asController(Request $request)
    {
        if ($request->ajax()) {
            $q_length = $request->length;
            $q_start = $request->start;
            $records_q = UserActivity::query();
            $total_records = $records_q->count();
            if ($q_length > 0) {
                $records_q = $records_q->limit($q_start + $q_length);
            }
            $records = $records_q->latest()->get();
            return DataTables::of($records)
                ->addColumn('user', function ($record) {
                    return $record->user?->getName();
                }) ->addColumn('created_at', function ($record) {
                    return dateTimeFormat($record->created_at);
                })
                ->addColumn('actions', function ($record) {
                    return view('layout.partial.actions', [
                        'record' => $record
                    ])->render();
                })
                ->rawColumns(['actions'])
                ->setFilteredRecords($total_records)
                ->setTotalRecords($total_records)
                ->make(true);
        }
        return view($this->view . '.index', get_defined_vars());
    }
}
