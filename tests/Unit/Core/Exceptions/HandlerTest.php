<?php

namespace Tests\Unit\Core\Exceptions;

use Core\Exceptions\ForbiddenException;
use Core\Exceptions\Handler;
use Core\Exceptions\NotFoundException;
use Monolog\Level;
use RuntimeException;
use Tests\TestCase;

class HandlerTest extends TestCase
{
    private string $logPath;
    private string $logFile;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logPath = sys_get_temp_dir() . '/logs';
        $this->logFile = $this->logPath . '/test.log';
        Handler::setLogPath($this->logFile);
        if (!is_dir($this->logPath)) {
            mkdir($this->logPath);
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        if (is_dir($this->logPath)) {
            remove_directory($this->logPath);
        }
    }

    public function test_log_exception(): void
    {
        $exception = new RuntimeException('Test exception');
        Handler::logException($exception, Level::Debug);
        $this->assertFileExists($this->logFile);
        $this->assertStringContainsString('Test exception', file_get_contents($this->logFile));
    }

    public function test_error_handler(): void
    {
        ob_start();
        Handler::errorHandler(E_USER_ERROR, 'Test error', 'test.php', 42);
        $actualOutput = ob_get_clean();
        $this->assertFileExists($this->logFile);
        $this->assertStringContainsString('Test error', file_get_contents($this->logFile));
        $this->assertStringContainsString('test.php', file_get_contents($this->logFile));
        $this->assertStringContainsString('42', file_get_contents($this->logFile));
        $this->assertSame('<h1>500 - server error</h1>', $actualOutput);
        $this->assertEquals(500, http_response_code());
    }

    public function test_exception_handler(): void
    {

        $exception = new RuntimeException('Test exception');
        ob_start();
        Handler::exceptionHandler($exception);
        $actualOutput = ob_get_clean();
        $this->assertFileExists($this->logFile);
        $this->assertStringContainsString('Test exception', file_get_contents($this->logFile));
        $this->assertSame('<h1>500 - server error</h1>', $actualOutput);
        $this->assertEquals(500, http_response_code());
    }

    public function test_exception_handler_404(): void
    {
        $exception = new NotFoundException('Test exception');
        ob_start();
        Handler::exceptionHandler($exception);
        $actualOutput = ob_get_clean();
        $this->assertFalse(file_exists($this->logFile));
        $this->assertSame('<h1>404 - Test exception</h1>', $actualOutput);
        $this->assertEquals(404, http_response_code());
    }

    public function test_exception_handler_403(): void
    {
        $exception = new ForbiddenException();
        ob_start();
        Handler::exceptionHandler($exception);
        $actualOutput = ob_get_clean();
        $this->assertFalse(file_exists($this->logFile));
        $this->assertSame('<h1>403 - Permission denied!</h1>', $actualOutput);
        $this->assertEquals(403, http_response_code());
    }
}

