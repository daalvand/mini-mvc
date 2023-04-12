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

    public function prepare(string $sql): PDOStatement
    {
        return $this->pdo->prepare($sql);
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }
}
