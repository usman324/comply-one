<?php

namespace App\Actions\Admin\Customer;

use App\Actions\BaseAction;
use App\Models\Customer;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class GetCustomerAction extends BaseAction
{
    use AsAction;

   
    protected string $title = 'Vendors';
    protected string $view = 'admin.customer';
    protected string $url = 'customers';
    protected string $permission = 'customer';

    public function handle(?int $id = null)
    {
        return  $id ? Customer::findOrFail($id) : new Customer();
    }

    public function asController(Request $request, $id = null)
    {
        $routeName = $request->route()->getName(); // Get the route name
        $record = $this->handle($id);
        $select_id = $request->select_id;
        return match ($routeName) {
            $this->view . '.create' => view($this->view . '.create', get_defined_vars()),
            $this->view . '.edit' => view($this->view . '.edit', get_defined_vars()),
            $this->view . '.show' => view($this->view . '.show', get_defined_vars()),
        };
    }
}
