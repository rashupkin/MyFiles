<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\BaseController;
use App\Core\Response;
use App\Services\FileService;

class FileController extends BaseController
{
    public function list(): Response
    {
        $fileList = App::getService(FileService::class)->list();

        return $this->jsonSuccesResponse($fileList);
    }

    public function get(string $id): Response
    {
        $file = App::getService(FileService::class)->get($id);

        return $this->jsonSuccesResponse($file);
    }

    public function add(array $data): Response
    {
        $files = $data["FILES"];

        App::getService(FileService::class)->add($files);

        return $this->successResponse(200);
    }

    public function rename(array $data): Response
    {
        $post = $data["POST"];

        App::getService(FileService::class)->rename($post);

        return $this->successResponse(200);
    }

    public function remove(string $id): Response
    {
        App::getService(FileService::class)->remove($id);

        return $this->successResponse(200);
    }

    public function getSharedFilesUsers(string $id): Response
    {
        $sharedFilesUser = App::getService(FileService::class)->shareGet($id);

        return $this->jsonSuccesResponse($sharedFilesUser);
    }

    public function shareFile(string $id, string $userId): Response
    {
        App::getService(FileService::class)->shareFile($id, $userId);

        return $this->successResponse(200);
    }

    public function deleteSharedFile(string $id, string $userId): Response
    {
        App::getService(FileService::class)->shareDelete($id);

        return $this->successResponse(200);
    }
}
