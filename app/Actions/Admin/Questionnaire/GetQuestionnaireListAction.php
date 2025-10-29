<?php

namespace App\Actions\Admin\Questionnaire;

use App\Actions\BaseAction;
use App\Models\Questionnaire;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GetQuestionnaireListAction extends BaseAction
{
    use AsAction;


    protected string $title = 'Questionnaire';
    protected string $view = 'admin.questionnaire';
    protected string $url = 'questionnaires';
    protected string $permission = 'questionnaire';


    public function asController(Request $request)
    {
        if ($request->select_id) {
            return $this->getSale($request->select_id);
        }
        if ($request->ajax()) {
            $q_length = $request->length;
            $q_start = $request->start;
            $records_q = Questionnaire::orderBy('created_at', 'desc');
            $total_records = $records_q->count();

            return DataTables::eloquent($records_q)
                ->addIndexColumn()
                ->addColumn('actions', function ($record) {
                    return view('admin.questionnaire.include.actions', [
                        'record' => $record,
                        'url' => $this->url,
                        'permission' => $this->permission
                    ])->render();
                })
                ->addColumn('questions_count', function ($record) {
                    return $record->questions->count();
                })->addColumn('section', function ($record) {
                    return $record->section->name;
                })
                ->addColumn('responses_count', function ($record) {
                    return $record->responses->count();
                })
                ->addColumn('status', function ($record) {
                    $badge = $record->status === 'active' ? 'success' : 'secondary';
                    return '<span class="badge bg-' . $badge . '">' . ucfirst($record->status) . '</span>';
                })
                ->addColumn('created_at', function ($record) {
                    return $record->created_at->format('M d, Y');
                })
                ->rawColumns(['actions', 'status'])
                ->make(true);
        }
        return view($this->view . '.index', get_defined_vars());
    }
}
