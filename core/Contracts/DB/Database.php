<?php

namespace Core\Contracts\DB;

use PDO;
use PDOStatement;

interface Database
{
    public function prepare(string $sql, array $options = []): PDOStatement;

    public function query(string $sql, int|null $fetchMode = null, mixed ...$fetch_mode_args): PDOStatement;

    public function exec(string $sql): int|false;

    public function pdo(): PDO;

    public function lastInsertId(?string $name = null): string|int;
}
