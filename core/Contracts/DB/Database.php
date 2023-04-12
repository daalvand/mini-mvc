<?php

namespace Core\Contracts\DB;

use PDO;
use PDOStatement;

interface Database
{
    /**
     * Prepares statement
     *
     * @param string $sql
     *
     * @return PDOStatement
     */
    public function prepare(string $sql): PDOStatement;

    /**
     * returns pdo
     *
     * @return PDO
     */
    public function pdo(): PDO;
}
