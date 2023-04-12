<?php

namespace Core\DB;

use Core\Contracts\DB\Migration;
use Core\Contracts\DB\Migrator as MigratorContract;
use Core\Contracts\DB\Database;
use PDO;

class Migrator implements MigratorContract
{
    public function __construct(
         protected Database $database,
         protected string $basePath
    ) {
    }

    public function applyMigrations(): void
    {
        $this->createMigrationsTable();
        $newMigrations = $this->getNewMigrations();
        foreach ($newMigrations as ['file' => $file, 'name' => $name]) {
            $instance = require $file;
            if ($instance instanceof Migration) {
                $this->database->pdo()->exec($instance->up());
            }
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations(array_column($newMigrations, 'name'));
        }
    }

    protected function createMigrationsTable(): void
    {
        $this->database->pdo()->exec(
             "CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )  ENGINE=INNODB;"
        );
    }

    protected function getAppliedMigrations(): bool|array
    {
        $statement = $this->database->pdo()->query("SELECT migration FROM migrations");
        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    protected function getNewMigrations(): array
    {
        $appliedMigrations = array_flip($this->getAppliedMigrations());
        $files             = glob($this->basePath . '/database/migrations/*.php');
        $newMigrations     = [];
        foreach ($files as $file) {
            $migrationName = pathinfo($file, PATHINFO_FILENAME);
            if (!isset($appliedMigrations[$migrationName])) {
                $newMigrations[] = ['file' => $file, 'name' => $migrationName];
            }
        }
        return $newMigrations;
    }

    protected function saveMigrations(array $newMigrations): void
    {
        $values = array_map(static fn($m) => "('$m')", $newMigrations);
        $query  = "INSERT INTO migrations (migration) VALUES " . implode(',', $values);
        $this->database->pdo()->exec($query);
    }

}
