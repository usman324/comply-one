<?php

namespace App\Actions\Admin\Customer;

use App\Actions\BaseAction;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Sale;
use App\Traits\CustomAction;
use Lorisleiva\Actions\ActionRequest;
use App\Traits\RespondsWithJson;
use Exception;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteCustomerAction extends BaseAction
{
    use AsAction, RespondsWithJson, CustomAction;


    protected string $title = 'Vendors';
    protected string $view = 'admin/customer';
    protected string $url = 'customers';
    protected string $permission = 'customer';

    public function handle(
        int $id
    ) {
        try {
            $record = Customer::findOrFail($id);
            $hasPayments = Payment::where('customer_id', $record->id)->exists();
            $hasSales = Sale::where('customer_id', $record->id)->exists();

            if ($hasPayments || $hasSales) {
                return $this->error('Record not deleted. This customer has Sales or Payments.');
            }
            $record->delete();
            return $this->success('Record Deleted Successfully');
        } catch (Exception $e) {
            return  $this->error($e->getMessage());
        }
    }

    public function asController(ActionRequest $request, $id)
    {
        return $this->handle($id);
    }
}
