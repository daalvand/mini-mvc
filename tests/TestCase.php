<?php

namespace Tests;

use Core\Contracts\App;
use Core\Contracts\Session;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase  extends PHPUnitTestCase
{
    protected App $app;

     protected function setUp(): void
     {
         $this->startApplication();
          parent::setUp();
     }

     protected function tearDown(): void
     {
         $this->app->get(Session::class)->destroy();
          parent::tearDown();
     }

     protected function startApplication(): void
     {
         $this->app = require __DIR__ . '/../bootstrap/app.php';
     }

    protected function assertDatabaseHas(string $table, array $conditions = []): void
    {
        $query = query_builder()->table($table);
        foreach ($conditions as $key => $value) {
            $query->where($key, '=', $value);
        }

        $this->assertTrue($query->exists());
    }

    protected function assertDatabaseMissing(string $table, array $conditions): void
    {
        $query = query_builder()->table($table);
        foreach ($conditions as $key => $value) {
            $query->where($key, '=', $value);
        }

        $this->assertFalse($query->exists());
    }
}
