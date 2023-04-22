<?php

namespace Core\DB\Schema;

use Core\Contracts\DB\Database;
use Core\Contracts\DB\Schema as SchemaContract;
use PDO;

class Schema implements SchemaContract
{
    public function __construct(protected Database $db)
    {
    }

    public function createTable(string $tableName, callable $callback): void
    {
        $table = new Table($tableName);
        $callback($table);

        $sql = "CREATE TABLE IF NOT EXISTS $tableName (";

        foreach ($table->getColumns() as $column) {
            $sql .= $column . ",";
        }

        $sql = rtrim($sql, ',');
        $sql .= ")";

        $this->db->exec($sql);
    }

    public function alterTable(string $tableName, callable $callback): void
    {
        $currentColumns = $this->getTableColumns($tableName);

        $table = new Table($tableName);
        $callback($table);

        $sql = "ALTER TABLE $tableName ";

        foreach ($table->getColumns() as $column) {
            $columnName = $column->getName();
            $columnType = $column->getType();

            if (isset($currentColumns[$columnName])) {
                // Column already exists, modify it
                $sql .= "MODIFY $columnName $columnType,";
            } else {
                // Column doesn't exist, add it
                $sql .= "ADD $columnName $columnType,";
            }
        }

        $sql = rtrim($sql, ',');
        $this->db->exec($sql);
    }

    public function dropTable(string $tableName): void
    {
        $this->db->exec("DROP TABLE IF EXISTS $tableName");
    }

    public function dropAllTables(): void
    {
        $tables = $this->db->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
        foreach ($tables as $table) {
            $this->dropTable($table);
        }
    }

    protected function getTableColumns(string $tableName): array
    {
        $stmt    = $this->db->query("SHOW COLUMNS FROM $tableName");
        $columns = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $columnName = $row['Field'];
            $columnType = $row['Type'];

            $columns[$columnName] = $columnType;
        }

        return $columns;
    }
}
