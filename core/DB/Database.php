<?php

namespace Core\DB;

use Core\Contracts\DB\Database as DatabaseContract;
use PDO;
use PDOStatement;

class Database implements DatabaseContract
{
    protected PDO $pdo;

    public function __construct(array $config)
    {
        $this->pdo = new PDO(...$config);
    }

    public function prepare(string $sql, array $options = []): PDOStatement
    {
        return $this->pdo->prepare($sql, $options);
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }

    public function query(string $sql, int|null $fetchMode = null, mixed ...$fetch_mode_args): PDOStatement
    {
        return $this->pdo->query($sql, $fetchMode, $fetch_mode_args);
    }

    public function exec(string $sql): int|false
    {
        return $this->pdo->exec($sql);
    }

    public function lastInsertId(?string $name = null): string|int
    {
        return $this->pdo->lastInsertId($name);
    }
}
