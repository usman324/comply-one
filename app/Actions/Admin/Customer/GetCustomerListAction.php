<?php

namespace App\Actions\Admin\Customer;

use App\Actions\BaseAction;
use App\Models\Customer;
use App\Models\Sale;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class GetCustomerListAction extends BaseAction
{
    use AsAction;


    protected string $title = 'Vendors';
    protected string $view = 'admin.customer';
    protected string $url = 'customers';
    protected string $permission = 'customer';


    public function asController(Request $request)
    {
        if ($request->select_id) {
            return $this->getSale($request->select_id);
        }
        if ($request->ajax()) {
            $q_length = $request->length;
            $q_start = $request->start;
            $records_q = Customer::query();
            $total_records = $records_q->count();
            if ($q_length > 0) {
                $records_q = $records_q->limit($q_start + $q_length);
            }
            $records = $records_q->get();
            return DataTables::of($records)
                ->addColumn('image', function ($record) {
                    $image_url = $record->getAvatar();
                    return "<img src='$image_url' style='height:30px !important'>";
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
    public function getSale($customer_id)
    {
        $records = Sale::where('customer_id', $customer_id)->where('status', 'delivered')->get();
        $title = 'select sale';
        return view('layout.partial.select', get_defined_vars());
    }
}
