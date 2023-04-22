<?php

namespace Core\Contracts\DB;

interface Schema
{
    public function createTable(string $tableName, callable $callback): void;

    public function alterTable(string $tableName, callable $callback): void;

    public function dropTable(string $tableName): void;

    public function dropAllTables(): void;
}
