<?php

namespace Core\Exceptions;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Throwable;

class Handler
{
    public static function errorHandler(int $errNo, string $errStr, string $errFile, int $errLine): never
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
        echo "<h1>500 - server error</h1>";
        exit;
    }

    public static function exceptionHandler(Throwable $e): never
    {
        http_response_code(500);
        if ($e instanceof NotFoundException || $e instanceof ForbiddenException) {
            http_response_code($e->getCode());
            echo "<h1>{$e->getMessage()}</h1>";
        } else {
            self::logException($e);
            echo "<h1>500 - server error</h1>";
        }
        exit;
    }

    protected static function getLogPath(): string
    {
        try {
            $config = app()->getConfig('log');
            $baseBase = app()->basePath();
            $logPath = merge_paths($baseBase, $config['path'], date('Y-m-d') . '.log');
        } catch (Throwable) {
            $logPath = __DIR__ . '/../../storage/logs/' . date('Y-m-d') . '.log';
        }
        return $logPath;
    }


    public static function logException(Throwable $e, Level $level = Level::Error): void
    {
        $logPath = self::getLogPath();
        $log = new Logger('app');
        $log->pushHandler(new StreamHandler($logPath, $level));
        $log->log($level, $e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTrace(),
            'code' => $e->getCode(),
            'exception' => get_class($e),
        ]);
    }
}
