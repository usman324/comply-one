<?php

namespace App\Actions\Admin\Workspace;

use App\Actions\BaseAction;
use App\Models\Workspace;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GetWorkspaceListAction extends BaseAction
{
    use AsAction;

    protected string $title = 'Workspace';
    protected string $view = 'admin.workspace';
    protected string $url = 'workspaces';
    protected string $permission = 'workspace';


    public function asController(Request $request)
    {
        if ($request->ajax()) {
            $q_length = $request->length;
            $q_start = $request->start;
            $records_q = Workspace::with(['owner', 'creator'])
                ->byName($request->name)
                ->byType($request->type)
                ->byStatus($request->status)
                ->latest();
            $total_records = $records_q->count();
            if ($q_length > 0) {
                $records_q = $records_q->limit($q_start + $q_length);
            }
            $records = $records_q->get();
            return DataTables::of($records)
                ->addColumn('image', function ($record) {
                    $image_url = $record->getAvatarUrl();
                    return "<img src='$image_url' style='height:50px !important; width:50px; object-fit:cover; border-radius:5px;'>";
                })
                ->addColumn('name', function ($record) {
                    return $record->name;
                })
                ->addColumn('workspace_number', function ($record) {
                    return $record->workspace_number;
                })
                ->addColumn('type', function ($record) {
                    return $record->getTypeBadge();
                })
                ->addColumn('status', function ($record) {
                    return $record->getStatusBadge();
                })
                ->addColumn('owner', function ($record) {
                    return $record->owner ? $record->owner->first_name . ' ' . $record->owner->last_name : 'N/A';
                })
                ->addColumn('created_at', function ($record) {
                    return $record->created_at->format('M d, Y');
                })
                ->addColumn('actions', function ($record) {
                    return view($this->view . '.include.actions', [
                        'record' => $record
                    ])->render();
                })
                ->rawColumns(['actions', 'image', 'status', 'type'])
                ->setFilteredRecords($total_records)
                ->setTotalRecords($total_records)
                ->make(true);
        }
        return view($this->view . '.index', get_defined_vars());
    }
}
