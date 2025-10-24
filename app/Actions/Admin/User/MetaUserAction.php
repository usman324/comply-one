<?php

namespace App\Actions\Admin\User;

use App\Actions\BaseAction;
use App\Models\ProductVariation;
use App\Models\UserUnit;
use Lorisleiva\Actions\Concerns\AsAction;

class MetaUserAction
{
    use AsAction;

    public function handle(
        $request,
        $record
    ) {
        UserUnit::where('user_id', $record->id)->delete();
        if ($request->units) {
            foreach ($request->units as $unit) {
                $variation =  new UserUnit();
                $variation->fill([
                    'user_id' => $record->id,
                    'unit_id' => $unit,
                ]);
                $variation->save();
            }
        }
    }
    public function asController($request, $record)
    {
        return $this->handle($request, $record);
    }
}
