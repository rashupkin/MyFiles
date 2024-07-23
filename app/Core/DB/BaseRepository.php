<?php

namespace App\Core\DB;

use App\Core\DB\Connection;
use App\Core\DB\IRepository;

abstract class BaseRepository implements IRepository
{
    protected Connection $connection;

    public function __construct()
    {
        $this->connection = new Connection();
    }

    public function getTableName(): string
    {
        return "users";
    }

    public function find(int $id): array
    {
        try {
            $db = $this->connection::getConnection();

            $userTableName = $this->getTableName();
            $stmt = $db->pdo->prepare("SELECT * FROM $userTableName WHERE id = :id");
            $stmt->execute(["id" => $id]);

            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $result;
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function findBy(array $params = [], int $limit = 50, int $offset = 0): array
    {
        try {
            $db = $this->connection::getConnection();

            $userTableName = $this->getTableName();
            $sql = "SELECT * FROM $userTableName";
            $paramsKeys = array_keys($params);

            if (!empty($params)) {
                $sql .= " WHERE";
            }

            foreach ($paramsKeys as $paramKey) {
                if (!strpos($sql, "AND")) {
                    $sql .= " $paramKey = :$paramKey";
                    continue;
                }

                $sql .= " AND $paramKey = :$paramKey";
            }

            $sql .= " ORDER BY id ASC LIMIT $limit OFFSET $offset";

            $stmt = $db->pdo->prepare($sql);

            foreach ($params as $paramKey => $paramValue) {
                $stmt->bindParam($paramKey, $paramValue, \PDO::PARAM_STR);
            }

            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $result;
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function findOneBy(array $params): array
    {
        try {
            $db = $this->connection::getConnection();

            $userTableName = $this->getTableName();
            $sql = "SELECT * FROM $userTableName WHERE";
            $paramsKeys = array_keys($params);

            foreach ($paramsKeys as $paramKey) {
                if (!strpos($sql, "AND")) {
                    $sql .= " $paramKey = :$paramKey";
                    continue;
                }

                $sql .= " AND $paramKey = :$paramKey";
            }

            $sql .= " LIMIT 1";

            $stmt = $db->pdo->prepare($sql);

            foreach ($params as $paramKey => $paramValue) {
                $stmt->bindParam($paramKey, $paramValue, \PDO::PARAM_STR);
            }

            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            return empty($result) ? [] : $result;
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}
