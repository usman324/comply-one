<?php

namespace App\Actions\Admin\GenerelSetting;

use App\Actions\BaseAction;
use App\Models\GeneralSetting;
use App\Traits\CustomAction;
use Lorisleiva\Actions\ActionRequest;
use App\Traits\RespondsWithJson;
use Exception;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateGenerelSettingAction extends BaseAction
{
    use AsAction;
    use RespondsWithJson;
    use CustomAction;

    protected string $title = 'General Setting';
    protected string $view = 'admin.general-setting';
    protected string $url = 'general-settings';
    protected string $permission = 'general_setting';

    public function handle(
        $request,
        int $id
    ) {
        try {
            $record = GeneralSetting::find($id);
            if ($request->update_mail) {
                $record->update([
                    'mail_template' => $request->mail_template
                ]);
                return  $this->success('Setting Updated Successfully');
            }
            $logo = $request->logo;
            $favicon = $request->favicon;
            $logo_name = '';
            $favicon_name = '';
            if ($logo) {
                if ($record->logo) {
                    deleteImage('general/' . $record->logo, $record->logo);
                }
                $name = rand(10, 100) . time() . '.' . $logo->getClientOriginalExtension();
                $logo->storeAs('general', $name);
                $logo_name = $name;
            }
            $favicon_name = '';
            if ($favicon) {
                if ($record->favicon) {
                    deleteImage('general/' . $record->favicon, $record->favicon);
                }

                $name = rand(10, 100) . time() . '.' . $favicon->getClientOriginalExtension();
                $favicon->storeAs('general', $name);
                $favicon_name = $name;
            }
            $record->update([
                'title' => $request->title,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'street' => $request->street,
                'street_no' => $request->street_no,
                'postal_code' => $request->postal_code,
                'website' => $request->website,
                'facebook' => $request->facebook,
                'youtube' => $request->youtube,
                'linkdin' => $request->linkdin,
                'instagram' => $request->instagram,
                'logo_height' => $request->logo_height,
                'lat' => $request->lat,
                'long' => $request->long,
                'logo' => $logo_name ? $logo_name : $record->logo,
                'favicon' => $favicon_name ? $favicon_name : $record->favicon,
            ]);
            return  $this->success('Setting Updated Successfully');
        } catch (Exception $e) {
            return  $this->error($e->getMessage());
        }
    }

    public function asController(ActionRequest $request, $id)
    {
        return $this->handle($request, $id);
    }
}
