<?php

namespace App\Actions\Admin\GenerelSetting;

use App\Actions\BaseAction;
use App\Traits\CustomAction;
use App\Traits\RespondsWithJson;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;


class GetGenerelSettingAction extends BaseAction
{
    use AsAction, RespondsWithJson;
    protected string $title = 'General Setting';
    protected string $view = 'admin.general-setting';
    protected string $url = 'general-settings';
    protected string $permission = 'general_setting';



    public function asController(Request $request)
    {
        return view($this->view . '.index', get_defined_vars());
    }
}
