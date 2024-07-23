<?php

namespace App\Controllers\Admin;

use App\Core\App;
use App\Core\BaseController;
use App\Core\Response;
use App\Services\UserService;

class AdminController extends BaseController
{
    public function userList(): Response
    {
        $userList = App::getService(UserService::class)->fullList();

        return $this->jsonSuccesResponse($userList);
    }

    public function userGet(int $id): Response
    {
        $user = App::getService(UserService::class)->fullGet($id);

        return $this->jsonSuccesResponse($user);
    }

    public function userDelete(int $id): Response
    {
        App::getService(UserService::class)->deleteOtherUser($id);

        return $this->successResponse(200);
    }

    public function userUpdate(array $data, int $id): Response
    {
        $post = $data["POST"];

        App::getService(UserService::class)->updateOtherUser($id, $post);

        return $this->successResponse(200);
    }
}
