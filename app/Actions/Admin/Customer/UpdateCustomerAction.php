<?php

namespace App\Actions\Admin\Customer;

use App\Actions\BaseAction;
use App\Models\Customer;
use App\Traits\CustomAction;
use Lorisleiva\Actions\ActionRequest;
use App\Traits\RespondsWithJson;
use Exception;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateCustomerAction extends BaseAction
{
    use AsAction, RespondsWithJson, CustomAction;

    protected string $title = 'Vendors';
    protected string $view = 'admin/customer';
    protected string $url = 'customers';
    protected string $permission = 'customer';

    public function rules(ActionRequest $request): array
    {

        return [
            'name' => 'required',
        ];
    }
    public function handle(
        $request,
        int $id
    ) {
        try {
            $record = Customer::findOrFail($id);
            $image = $request->image;
            $avatar = null;
            $image_name = null;
            if ($image) {
                deleteImage('customer/' . $record?->avatar, $record?->avatar);
                deleteImage('customer/' . $record?->image, $record?->image);
                $image_name = saveImage('customer', $image);
                $avatar = myImgFit('customer/', $image, 250, 250);
            }
            $record->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'image' => $image_name ? $image_name : $record->image,
                'avatar' => $avatar ? $avatar : $record->avatar,
            ]);
            return  $this->success('Record Updated Successfully');
        } catch (Exception $e) {
            return  $this->error($e->getMessage());
        }
    }

    public function asController(ActionRequest $request, $id)
    {
        return $this->handle($request, $id);
    }
}
