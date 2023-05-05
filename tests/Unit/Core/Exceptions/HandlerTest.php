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
        $this->assertSame("\033[31m Error: 256 - Test error in test.php on line 42 \033[0m", $actualOutput);
    }

    public function test_exception_handler(): void
    {

        $exception = new RuntimeException('Test exception');
        $code      = $exception->getCode();
        $class     = RuntimeException::class;
        $message   = $exception->getMessage();
        $trace     = $exception->getTraceAsString();
        ob_start();
        Handler::exceptionHandler($exception);
        $actualOutput = ob_get_clean();
        $this->assertFileExists($this->logFile);
        $this->assertStringContainsString('Test exception', file_get_contents($this->logFile));
        $this->assertSame("\033[31m Exception $class - $code - $message:\n $trace \033[0m", $actualOutput);
    }

    public function test_exception_handler_404(): void
    {
        $exception = new NotFoundException();
        $code      = $exception->getCode();
        $class     = NotFoundException::class;
        $message   = $exception->getMessage();
        $trace     = $exception->getTraceAsString();
        ob_start();
        Handler::exceptionHandler($exception);
        $actualOutput = ob_get_clean();
        $this->assertFileExists($this->logFile);
        $this->assertStringContainsString($message, file_get_contents($this->logFile));
        $this->assertSame("\033[31m Exception $class - $code - $message:\n $trace \033[0m", $actualOutput);
    }

    public function test_exception_handler_403(): void
    {
        $exception = new ForbiddenException();
        $code      = $exception->getCode();
        $class     = ForbiddenException::class;
        $message   = $exception->getMessage();
        $trace     = $exception->getTraceAsString();
        ob_start();
        Handler::exceptionHandler($exception);
        $actualOutput = ob_get_clean();
        $this->assertFileExists($this->logFile);
        $this->assertStringContainsString($message, file_get_contents($this->logFile));
        $this->assertSame("\033[31m Exception $class - $code - $message:\n $trace \033[0m", $actualOutput);
    }
}

