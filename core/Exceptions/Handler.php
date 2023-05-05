<?php

namespace Core\Exceptions;

use Core\Http\Response;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Throwable;

class Handler
{
    protected static string|null $logPath = null;

    public static function bootstrap(): void
    {
        // Set the error reporting level to the strictest level
        error_reporting(-1);

        // Register a custom error handler
        set_error_handler([self::class, 'errorHandler']);
        // Register a custom exception handler
        set_exception_handler([self::class, 'exceptionHandler']);
    }


    public static function errorHandler(int $errNo, string $errStr, string $errFile, int $errLine): void
    {
        $logPath = self::getLogPath();
        // Log the error using Monolog
        $log = new Logger('error');
        $log->pushHandler(new StreamHandler($logPath, Level::Error));
        $log->error($errStr, [
             'file' => $errFile,
             'line' => $errLine,
             'code' => $errNo,
        ]);
        (new Response('<h1>500 - server error</h1>', 500))->send();
    }

    public static function exceptionHandler(Throwable $e): void
    {
        $content = '<h1>500 - server error</h1>';
        $code    = 500;
        if ($e instanceof NotFoundException || $e instanceof ForbiddenException) {
            $content = '<h1>' . $e->getCode() . ' - ' . $e->getMessage() . '</h1>';
            $code    = $e->getCode();
        } else {
            self::logException($e);
        }
        (new Response($content, $code))->send();
    }


    public static function setLogPath(string $logPath): void
    {
        self::$logPath = $logPath;
    }

    protected static function getLogPath(): string
    {
        if (self::$logPath) {
            return self::$logPath;
        }
        try {
            $config        = app()->getConfig('log');
            $baseBase      = app()->basePath();
            self::$logPath = merge_paths($baseBase, $config['path'], date('Y-m-d') . '.log');
        } catch (Throwable) {
            self::$logPath = __DIR__ . '/../../storage/logs/' . date('Y-m-d') . '.log';
        }
        return self::$logPath;
    }


    public static function logException(Throwable $e, Level $level = Level::Error): void
    {
        $logPath = self::getLogPath();
        $log     = new Logger('app');
        $log->pushHandler(new StreamHandler($logPath, $level));
        $log->log($level, $e->getMessage(), [
             'file'      => $e->getFile(),
             'line'      => $e->getLine(),
             'trace'     => $e->getTrace(),
             'code'      => $e->getCode(),
             'exception' => get_class($e),
        ]);
    }
}
