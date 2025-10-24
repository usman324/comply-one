<?php

namespace App\Actions\Admin\Customer;

use App\Actions\BaseAction;
use App\Models\Customer;
use App\Traits\CustomAction;
use Lorisleiva\Actions\ActionRequest;
use App\Traits\RespondsWithJson;
use Exception;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreCustomerAction extends BaseAction
{
    use AsAction, RespondsWithJson, CustomAction;

    protected string $title = 'Vendors';
    protected string $view = 'admin/customer';
    protected string $url = 'customers';
    protected string $permission = 'customer';


    public function rules(): array
    {
        return [
            'name' => 'required',
        ];
    }
    public function handle(
        $request,
    ) {
        try {
            $image = $request->image;
            $image_name = null;
            $avatar = null;
            if ($image) {
                $image_name = saveImage('customer', $image);
                $avatar = myImgFit('customer/', $image, 250, 250);
            }
            $record = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'image' => $image_name,
                'avatar' => $avatar,
            ]);
            return  $this->success('Record Added Successfully');
        } catch (Exception $e) {
            return  $this->error($e->getMessage());
        }
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request);
    }
}
