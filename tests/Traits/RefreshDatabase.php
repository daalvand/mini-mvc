<?php

namespace Tests\Traits;

use Core\Contracts\DB\Migrator;
use Tests\TestCase;

/**
 * @mixin TestCase
 */
trait RefreshDatabase
{
    protected function refreshDatabase(): void
    {
        app()->get(Migrator::class)->rollbackMigrations();
        app()->get(Migrator::class)->applyMigrations();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshDatabase();
    }

    protected function tearDown(): void
    {
        $this->app->get(Migrator::class)->rollbackMigrations();
        parent::tearDown();
    }

}

