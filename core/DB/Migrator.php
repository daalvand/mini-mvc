<?php

namespace Core\DB;

use Core\Contracts\DB\Migration;
use Core\Contracts\DB\Migrator as MigratorContract;
use Core\Contracts\DB\QueryBuilder;
use Core\DB\Schema\Table;

class Migrator implements MigratorContract
{
    public function __construct(
         protected QueryBuilder $builder,
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
                $instance->up();
            }
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations(array_column($newMigrations, 'name'));
        }
    }

    public function rollbackMigrations(): void
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();
        foreach ($appliedMigrations as $migration) {
            $instance = require $this->basePath . '/database/migrations/' . $migration['migration'] . '.php';
            if ($instance instanceof Migration) {
                $instance->down();
            }
        }

        if (!empty($appliedMigrations)) {
            $this->deleteMigrations($appliedMigrations);
        }
    }

    protected function createMigrationsTable(): void
    {
        schema()->createTable('migrations', function (Table $table) {
            $table->id();
            $table->string('migration');
            $table->timestampCurrent('created_at');
        });
    }

    protected function getAppliedMigrations(): false|array
    {
        return $this->builder->table('migrations')->orderBy('id', 'DESC')->get();
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
        $data = [];
        foreach ($newMigrations as $newMigration) {
            $data[] = ['migration' => $newMigration];
        }
        $this->builder->table('migrations')->insert($data);
    }

    private function deleteMigrations(array $appliedMigrations): void
    {
        $names = array_column($appliedMigrations, 'migration');
        $this->builder->table('migrations')->whereIn('migration', $names)->delete();
    }
}
