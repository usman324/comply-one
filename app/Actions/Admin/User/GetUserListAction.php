<?php

namespace App\Actions\Admin\User;

use App\Actions\BaseAction;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class GetUserListAction extends BaseAction
{
    use AsAction;

    protected string $title = 'User';
    protected string $view = 'admin.user';
    protected string $url = 'users';
    protected string $permission = 'user';


    public function asController(Request $request)
    {
        if ($request->ajax()) {
            $q_length = $request->length;
            $q_start = $request->start;
            $records_q = User::when(!getUser()->hasRole('admin'), function ($q) {
                $q->where('workspace_id', getUser()->workspace_id);
            })
                ->byName($request->name)
                ->byEmail($request->email)
                ->byPhone($request->phone)
                ->byStatus($request->status)
                ->where('email', '!=', getUser()->email)
                ->latest();
            $total_records = $records_q->count();
            if ($q_length > 0) {
                $records_q = $records_q->limit($q_start + $q_length);
            }
            $records = $records_q->get();
            return DataTables::of($records)
                ->addColumn('image', function ($record) {
                    $image_url = $record->getImage();
                    return "<img src='$image_url' style='height:50px !important'>";
                })
                ->addColumn('workspace', function ($record) {
                    return $record?->workspace?->name;
                })->addColumn('name', function ($record) {
                    return $record?->getName();
                })->addColumn('role', function ($record) {
                    return $record->getRoleNames()?->first();
                })->addColumn('status', function ($record) {
                    return $record?->getStatus();
                })
                ->addColumn('actions', function ($record) {
                    return view($this->view . '.include.actions', [
                        'record' => $record
                    ])->render();
                })
                ->rawColumns(['actions', 'image', 'status'])
                ->setFilteredRecords($total_records)
                ->setTotalRecords($total_records)
                ->make(true);
        }
        $roles = Role::where('is_hidden', false)->get();
        return view($this->view . '.index', get_defined_vars());
    }
}
