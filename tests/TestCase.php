<?php

namespace Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase  extends PHPUnitTestCase
{
     protected function setUp(): void
     {
         $this->startApplication();
          parent::setUp();
     }

     protected function tearDown(): void
     {
          parent::tearDown();
     }

     protected function startApplication(): void
     {
     }
}
