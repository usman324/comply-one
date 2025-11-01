<?php

namespace App\Actions\Admin\Workspace\User;

use App\Actions\BaseAction;
use App\Models\Workspace;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsController;
use Yajra\DataTables\Facades\DataTables;

class GetWorkspaceUserListAction extends BaseAction
{
    use AsAction;
    use AsController;

    protected string $title = 'Workspace User';
    protected string $view = 'admin.workspace.user';
    public string $url = 'workspaces';
    protected string $permission = 'workspace';


    public function asController(Request $request, Workspace $workspace)
    {
        $this->url = url($this->url.'/'.$workspace->id.'/users');

        if ($request->ajax()) {
            $workspace->load('users.workspace');
            $records_q = $workspace->users();
            $total_records = $records_q->count();
            $records = $records_q;
            return DataTables::eloquent($records)
                ->addColumn('image', function ($record) {
                    $image_url = $record->workspace->getAvatarUrl();
                    return "<img src='$image_url' style='height:50px !important; width:50px; object-fit:cover; border-radius:5px;'>";
                })
                ->addColumn('name', function ($record) {
                    return $record->full_name;
                })
                ->addColumn('workspace', function ($record) {
                    return $record->workspace->name;
                })
                ->addColumn('workspace_number', function ($record) {
                    return $record->workspace->workspace_number;
                })
                ->addColumn('type', function ($record) {
                    return $record->workspace->getTypeBadge();
                })
                ->addColumn('status', function ($record) {
                    return $record->workspace->getStatusBadge();
                })
                ->addColumn('owner', function ($record) {
                    return $record->workspace->owner ? $record->workspace->owner->first_name . ' ' . $record->workspace->owner->last_name : 'N/A';
                })
                ->addColumn('role', function ($record) {
                    return $record->getRoleNames()[0];
                })
                ->addColumn('created_at', function ($record) {
                    return $record->created_at->format('M d, Y');
                })
                ->addColumn('actions', function ($record) {
                    return view($this->view . '.include.actions', [
                        'record' => $record,
                        'workspace' => $record->workspace,
                    ])->render();
                })
                ->rawColumns(['actions', 'image', 'status', 'type'])
                ->setFilteredRecords($total_records)
                ->setTotalRecords($total_records)
                ->make(true);
        }
        return view($this->view. '.index', get_defined_vars());
    }
}
