<?php

namespace App\Actions;

use App\Traits\RespondsWithJson;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsController;

class BaseAction
{
    use AsAction;
    use AsController;
    use RespondsWithJson;

    protected string $title = '';
    protected string $view = '';
    protected string $url = '/';
    protected string $permission = '';

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
