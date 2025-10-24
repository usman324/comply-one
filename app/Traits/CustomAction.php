<?php

namespace App\Traits;

use Lorisleiva\Actions\Concerns\AsAction;

trait CustomAction
{
    use AsAction;
    use RespondsWithJson;


    public function htmlResponse(array $response)
    {
        return $this->response($response);
    }

    public function jsonResponse($response)
    {
        return $this->response($response);
    }

    private function response($response)
    {
        return RespondsWithJson::getDefaultResponse($response);
    }

}
