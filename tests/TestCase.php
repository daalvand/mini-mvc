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
}
