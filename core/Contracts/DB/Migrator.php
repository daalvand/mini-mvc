<?php

namespace Core\Contracts\DB;

use Throwable;

interface Migrator
{
    public function applyMigrations(): void;

    public function rollbackMigrations(): void;
}
