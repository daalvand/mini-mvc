<?php

namespace Core\Contracts\DB;

use Throwable;

interface Migrator
{
    /**
     * @throws Throwable
     * @return void
     */
    public function applyMigrations(): void;
}
