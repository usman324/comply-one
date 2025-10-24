<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\ActionRequest;
use App\Support\Helpers\ResponseHelper;
use App\Traits\RespondsWithJson;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Lorisleiva\Actions\Concerns\AsAction;

class BaseAction
{
    use AsAction, RespondsWithJson;

    protected string $title;
    protected string $view;
    protected string $url;
    protected string $permission;

    public function __construct()
    {
        view()->share([
            'url' => url($this->url),
            'view' => $this->view,
            'title' => $this->title,
            'permission' => $this->permission,
        ]);
    }
}
