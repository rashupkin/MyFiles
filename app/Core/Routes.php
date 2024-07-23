<?php

namespace App\Core;

use App\Core\Interfaces\ControllerInterface;
use App\Core\Interfaces\IService;
use App\Core\Interfaces\UserInterface;
use App\Core\Interfaces\RouteInterface;

class Routes implements IService, ControllerInterface, UserInterface, RouteInterface
{
    private array $routesList = [
        "/^\/$/" => [
            self::HTTP_METHOD => Request::GET_METHOD,
            self::CONTROLLER_METHOD => "index",
            self::CONTROLLER_NAME => self::INDEX_CONTROLLER,
            self::ALLOWED_ROLES => [self::COMMON_ROLE, self::ADMIN_ROLE, self::NO_ROLE]
        ],
        "/^\/users\/list$/" => [
            self::HTTP_METHOD => Request::GET_METHOD,
            self::CONTROLLER_METHOD => "list",
            self::CONTROLLER_NAME => self::USER_CONTROLLER,
            self::ALLOWED_ROLES => [self::COMMON_ROLE, self::ADMIN_ROLE, self::NO_ROLE]
        ],
        "/^\/users\/get\/[0-9]$/" => [
            self::HTTP_METHOD => Request::GET_METHOD,
            self::CONTROLLER_METHOD => "get",
            self::CONTROLLER_NAME => self::USER_CONTROLLER,
            self::PARAMS => ["id"],
            self::ALLOWED_ROLES => [self::COMMON_ROLE, self::ADMIN_ROLE, self::NO_ROLE]
        ],
        "/^\/users\/update$/" => [
            self::HTTP_METHOD => Request::POST_METHOD,
            self::CONTROLLER_METHOD => "update",
            self::CONTROLLER_NAME => self::USER_CONTROLLER,
            self::ALLOWED_ROLES => [self::COMMON_ROLE, self::ADMIN_ROLE, self::NO_ROLE]
        ],
        "/^\/users\/login$/" => [
            self::HTTP_METHOD => Request::POST_METHOD,
            self::CONTROLLER_METHOD => "login",
            self::CONTROLLER_NAME => self::USER_CONTROLLER,
            self::ALLOWED_ROLES => [self::COMMON_ROLE, self::ADMIN_ROLE, self::NO_ROLE]
        ],
        "/^\/users\/reset_password$/" => [
            self::HTTP_METHOD => Request::GET_METHOD,
            self::CONTROLLER_METHOD => "resetPassword",
            self::CONTROLLER_NAME => self::USER_CONTROLLER,
            self::ALLOWED_ROLES => [self::COMMON_ROLE, self::ADMIN_ROLE]
        ],
        "/^\/users\/logout$/" => [
            self::HTTP_METHOD => Request::GET_METHOD,
            self::CONTROLLER_METHOD => "logout",
            self::CONTROLLER_NAME => self::USER_CONTROLLER,
            self::ALLOWED_ROLES => [self::COMMON_ROLE, self::ADMIN_ROLE]
        ],
        "/^\/admin\/users\/list$/" => [
            self::HTTP_METHOD => Request::GET_METHOD,
            self::CONTROLLER_METHOD => "userList",
            self::CONTROLLER_NAME => self::ADMIN_CONTROLLER,
            self::ALLOWED_ROLES => [self::ADMIN_ROLE]
        ],
        "/^\/admin\/users\/get\/[0-9]$/" => [
            self::HTTP_METHOD => Request::GET_METHOD,
            self::CONTROLLER_METHOD => "userGet",
            self::CONTROLLER_NAME => self::ADMIN_CONTROLLER,
            self::PARAMS => ["id"],
            self::ALLOWED_ROLES => [self::ADMIN_ROLE]
        ],
        "/^\/admin\/users\/delete\/[0-9]$/" => [
            self::HTTP_METHOD => Request::POST_METHOD,
            self::CONTROLLER_METHOD => "userDelete",
            self::CONTROLLER_NAME => self::ADMIN_CONTROLLER,
            self::PARAMS => ["id"],
            self::ALLOWED_ROLES => [self::ADMIN_ROLE]
        ],
        "/^\/admin\/users\/update\/[0-9]$/" => [
            self::HTTP_METHOD => Request::POST_METHOD,
            self::CONTROLLER_METHOD => "userUpdate",
            self::CONTROLLER_NAME => self::ADMIN_CONTROLLER,
            self::PARAMS => ["id"],
            self::ALLOWED_ROLES => [self::ADMIN_ROLE]
        ],
        "/^\/files\/list$/" => [
            self::HTTP_METHOD => Request::GET_METHOD,
            self::CONTROLLER_METHOD => "list",
            self::CONTROLLER_NAME => self::FILE_CONTROLLER,
            self::ALLOWED_ROLES => [self::COMMON_ROLE, self::ADMIN_ROLE]
        ],
        "/^\/files\/get\/.*$/" => [
            self::HTTP_METHOD => Request::GET_METHOD,
            self::CONTROLLER_METHOD => "get",
            self::CONTROLLER_NAME => self::FILE_CONTROLLER,
            self::PARAMS => ["id"],
            self::ALLOWED_ROLES => [self::COMMON_ROLE, self::ADMIN_ROLE]
        ],
        "/^\/files\/add$/" => [
            self::HTTP_METHOD => Request::POST_METHOD,
            self::CONTROLLER_METHOD => "add",
            self::CONTROLLER_NAME => self::FILE_CONTROLLER,
            self::ALLOWED_ROLES => [self::COMMON_ROLE, self::ADMIN_ROLE]
        ],
        "/^\/files\/rename$/" => [
            self::HTTP_METHOD => Request::POST_METHOD,
            self::CONTROLLER_METHOD => "rename",
            self::CONTROLLER_NAME => self::FILE_CONTROLLER,
            self::ALLOWED_ROLES => [self::COMMON_ROLE, self::ADMIN_ROLE]
        ],
        "/^\/files\/remove\/.*$/" => [
            self::HTTP_METHOD => Request::POST_METHOD,
            self::CONTROLLER_METHOD => "remove",
            self::CONTROLLER_NAME => self::FILE_CONTROLLER,
            self::PARAMS => ["id"],
            self::ALLOWED_ROLES => [self::COMMON_ROLE, self::ADMIN_ROLE]
        ],
        "/^\/files\/share\/.*\/.*$/" => [
            self::HTTP_METHOD => Request::POST_METHOD,
            self::CONTROLLER_METHOD => "shareFile",
            self::CONTROLLER_NAME => self::FILE_CONTROLLER,
            self::PARAMS => ["id", "user_id"],
            self::ALLOWED_ROLES => [self::COMMON_ROLE, self::ADMIN_ROLE]
        ],
        "/^\/files\/share\/delete\/.*\/.*$/" => [
            self::HTTP_METHOD => Request::POST_METHOD,
            self::CONTROLLER_METHOD => "deleteSharedFile",
            self::CONTROLLER_NAME => self::FILE_CONTROLLER,
            self::PARAMS => ["id", "user_id"],
            self::ALLOWED_ROLES => [self::COMMON_ROLE, self::ADMIN_ROLE]
        ],
        "/^\/files\/share\/.*$/" => [
            self::HTTP_METHOD => Request::GET_METHOD,
            self::CONTROLLER_METHOD => "getSharedFilesUsers",
            self::CONTROLLER_NAME => self::FILE_CONTROLLER,
            self::PARAMS => ["id"],
            self::ALLOWED_ROLES => [self::COMMON_ROLE, self::ADMIN_ROLE]
        ],
        "/^\/directories\/add$/" => [
            self::HTTP_METHOD => Request::POST_METHOD,
            self::CONTROLLER_METHOD => "add",
            self::CONTROLLER_NAME => self::DIRECTORY_CONTROLLER,
            self::ALLOWED_ROLES => [self::COMMON_ROLE, self::ADMIN_ROLE]
        ],
        "/^\/directories\/rename$/" => [
            self::HTTP_METHOD => Request::POST_METHOD,
            self::CONTROLLER_METHOD => "rename",
            self::CONTROLLER_NAME => self::DIRECTORY_CONTROLLER,
            self::ALLOWED_ROLES => [self::COMMON_ROLE, self::ADMIN_ROLE]
        ],
        "/^\/directories\/get\/.*$/" => [
            self::HTTP_METHOD => Request::GET_METHOD,
            self::CONTROLLER_METHOD => "get",
            self::CONTROLLER_NAME => self::DIRECTORY_CONTROLLER,
            self::PARAMS => ["id"],
            self::ALLOWED_ROLES => [self::COMMON_ROLE, self::ADMIN_ROLE]
        ],
        "/^\/directories\/delete\/.*$/" => [
            self::HTTP_METHOD => Request::POST_METHOD,
            self::CONTROLLER_METHOD => "delete",
            self::CONTROLLER_NAME => self::DIRECTORY_CONTROLLER,
            self::PARAMS => ["id"],
            self::ALLOWED_ROLES => [self::COMMON_ROLE, self::ADMIN_ROLE]
        ],
    ];

    public function get(): array
    {
        return $this->routesList;
    }
}
