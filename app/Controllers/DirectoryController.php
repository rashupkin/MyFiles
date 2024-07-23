<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\BaseController;
use App\Core\Response;
use App\Services\DirectoryService;

class DirectoryController extends BaseController
{
    public function add(array $data): Response
    {
        $post = $data["POST"];

        App::getService(DirectoryService::class)->add($post);

        return $this->successResponse(200);
    }

    public function rename(array $data): Response
    {
        $post = $data["POST"];

        App::getService(DirectoryService::class)->rename($post);

        return $this->successResponse(200);
    }

    public function get(string $id): Response
    {
        $directory = App::getService(DirectoryService::class)->get($id);

        return $this->jsonSuccesResponse($directory);
    }

    public function delete(string $id): Response
    {
        App::getService(DirectoryService::class)->delete($id);

        return $this->successResponse(200);
    }
}
