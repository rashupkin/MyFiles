<?php

namespace App\Core;

use App\Core\Service\ResponseService;

class BaseController
{
    public function jsonSuccesResponse(array $json)
    {
        return App::getService(ResponseService::class)->getSuccessWithData($json);
    }

    public function successResponse(int $code)
    {
        return App::getService(ResponseService::class)->getCode($code);
    }

    public function errorResponse(string $errorMsg, int $code)
    {
        return App::getService(ResponseService::class)->getError($errorMsg, 500);
    }

    public function successViewResponse(string $view, int $code)
    {
        return App::getService(ResponseService::class)->getSuccessView($view, $code);
    }
}
