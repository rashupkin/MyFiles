<?php

namespace App\Core;

class Request
{
    private array $data;
    private string $route;
    private string $method;

    // http methods
    const GET_METHOD = "GET";
    const POST_METHOD = "POST";

    public function __construct(string $route, string $method)
    {
        $this->route = $route;
        $this->method = $method;
        $data = [];
        count($_GET) > 0 ? $data["GET"] = $_GET : null;
        count($_POST) > 0 ? $data["POST"] = $_POST : null;
        count($_FILES) > 0 ? $data["FILES"] = $_FILES : null;
        $this->data = count($data) > 0 ? $data : [];
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
