<?php

namespace App\Core\Service;

use App\Core\Interfaces\IService;
use App\Core\Response;

class ResponseService implements IService
{
    public function getError(string $msg, int $code): Response
    {
        return new Response(["status" => ["code" => $code, "msg" => $msg]]);
    }

    public function getSuccessWithData($result): Response
    {
        return new Response(["data" => json_encode($result), "status" => ["code" => 200]]);
    }

    public function getCode(int $code): Response
    {
        return new Response(["status" => ["code" => $code]]);
    }

    public function getSuccessView(string $view, int $code)
    {
        return new Response(["data" => ["view" => $view], "status" => ["code" => $code]]);
    }
}
