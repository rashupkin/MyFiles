<?php

namespace App\Core;

class Response
{
    private string|array $data = "";
    private array $headers = [];
    private array $statusInfo = [];

    public function __construct(array $params)
    {
        $this->setData($params["data"] ?? "");
        $this->setHeaders($params["headers"] ?? []);
        $this->setStatusInfo($params["status"] ?? []);
    }

    public function setData(string|array $data): void
    {
        if (is_array($data)) {
            $this->data = $data;
            return;
        }

        json_decode($data);
        $isJson = json_last_error() === JSON_ERROR_NONE;

        if ($isJson) {
            $this->data = $data;
            $this->setHeaders(["Content-Type: application/json"]);
            return;
        }

        $this->data = $data;
    }

    public function setHeaders(array $headers): void
    {
        if (sizeof($headers) === 0)
            return;

        $this->headers = $headers;
    }

    public function setStatusInfo(array $statusInfo): void
    {
        $this->statusInfo = $statusInfo;
    }

    public function getData(): string|array
    {
        return $this->data;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getStatusInfo(): array
    {
        return $this->statusInfo;
    }
}
