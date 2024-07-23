<?php

namespace App\Core\Service;

class BaseService
{
    public static function getPath(): string
    {
        return $_ENV["DIR"] . "/" . $_ENV["DB_DIRECTORY"] . (isset($_SESSION["email"]) ? "/" . $_SESSION["email"] : "");
    }
}
