<?php

namespace App\Core\DB;

use PDO;

class Connection
{
    private static $instance;
    public PDO $pdo;

    public function __construct()
    {
        if (is_null(self::$instance)) {
            $DB_NAME = $_ENV["DB_NAME"];
            $DB_PASS = $_ENV["DB_PASS"];
            $DB_USER = $_ENV["DB_USER"];
            $DB_HOST = $_ENV["DB_HOST"];

            $this->pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8", $DB_USER, $DB_PASS);
        }
    }

    public static function getConnection(): Connection
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }
}
