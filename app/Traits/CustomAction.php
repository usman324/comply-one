<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

trait CustomAction
{
    use AsAction, RespondsWithJson;


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
