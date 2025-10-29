<?php

namespace App\Actions\Admin\Section;

use App\Actions\BaseAction;
use App\Models\Section;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class GetSectionListAction extends BaseAction
{
    use AsAction;


    protected string $title = 'Section';
    protected string $view = 'admin.section';
    protected string $url = 'sections';
    protected string $permission = 'section';


    public function asController(Request $request)
    {
        if ($request->ajax()) {
            $q_length = $request->length;
            $q_start = $request->start;
            $records_q = Section::query();
            $total_records = $records_q->count();

            $records = $records_q;
            return DataTables::eloquent($records)
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
