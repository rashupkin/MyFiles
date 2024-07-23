<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\BaseController;
use App\Core\Response;
use App\Core\User\CurrentUser;
use App\Core\User\User;
use App\Services\DirectoryService;
use App\Services\UserService;

class UserController extends BaseController
{
    public function list(): Response
    {
        $userList = App::getService(UserService::class)->partialList();

        return $this->jsonSuccesResponse($userList);
    }

    public function get(int $id): Response
    {
        $user = App::getService(UserService::class)->partialGet($id);

        return $this->jsonSuccesResponse($user);
    }

    public function update(array $data): Response
    {
        $post = $data["POST"];

        App::getService(UserService::class)->updateCurrentUser($post);

        return $this->successResponse(200);
    }

    public function login(array $data): Response
    {
        $post = $data["POST"];

        $email = $post["email"];
        $password = $post["password"];

        $id = App::getService(UserService::class)->login($email, $password);

        if (is_int($id)) {
            $currentUser = CurrentUser::getInstance();

            if (!$currentUser->getUser()) {
                $user = new User($id, $password, $email);
                $currentUser->setUser($user);

                App::getService(DirectoryService::class)->create();

                return $this->successResponse(200);
            }
        }

        return $this->successResponse(404);
    }

    public function logout(): Response
    {
        App::getService(DirectoryService::class)->delete($_SESSION["email"]);

        session_destroy();

        return $this->successResponse(200);
    }

    public function resetPassword(): Response
    {
        App::getService(UserService::class)->resetPassword();

        return $this->successResponse(200);
    }
}
