<?php

namespace App\Core\Security;

use App\Core\App;
use App\Core\Response;
use App\Core\Service\ResponseService;
use App\Repository\UserRepository;

class Security
{
    const ACCESS_ERROR = "you are not logged in or you are not allowed to enter";

    public function accessUser(array $allowedRoles): Response
    {
        if (in_array("no role", $allowedRoles)) {
            return App::getService(ResponseService::class)->getCode(200);
        } else if (empty($_SESSION)) {
            return App::getService(ResponseService::class)->getError(self::ACCESS_ERROR, 403);
        }

        $baseRepository = App::getRepository(UserRepository::class);

        try {
            $baseRepository->findOneBy(["email" => $_SESSION["email"]]);
        } catch (\PDOException $e) {
            return App::getService(ResponseService::class)->getError($e->getMessage(), 500);
        }

        return App::getService(ResponseService::class)->getCode(200);
    }
}
