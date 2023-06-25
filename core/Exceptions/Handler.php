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
        set_error_handler([static::class, 'errorHandler']);
        // Register a custom exception handler
        set_exception_handler([static::class, 'exceptionHandler']);
    }


    public static function errorHandler(int $errNo, string $errStr, string $errFile, int $errLine): void
    {
        static::logError($errStr, $errFile, $errLine, $errNo);
        static::renderError($errNo, $errStr, $errFile, $errLine);
    }

    public static function exceptionHandler(Throwable $e): void
    {
        static::logException($e);
        static::renderException($e);
    }


    public static function setLogPath(string $logPath): void
    {
        static::$logPath = $logPath;
    }

    protected static function getLogPath(): string
    {
        if (static::$logPath) {
            return static::$logPath;
        }
        try {
            $config          = app()->getConfig('log');
            $baseBase        = app()->basePath();
            static::$logPath = merge_paths($baseBase, $config['path'], date('Y-m-d') . '.log');
        } catch (Throwable) {
            static::$logPath = __DIR__ . '/../../storage/logs/' . date('Y-m-d') . '.log';
        }
        return static::$logPath;
    }


    public static function logException(Throwable $e, Level $level = Level::Error): void
    {
        $logPath = static::getLogPath();
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

    protected static function renderError(int $errNo, string $errStr, string $errFile, int $errLine): void
    {
        if (PHP_SAPI === 'cli') {
            echo "\033[31m Error: $errNo - $errStr in $errFile on line $errLine \033[0m";
        } else {
            if (app()->getConfig('debug')) {
                $content = "<h1>Error: $errNo - $errStr</h1> in <b>$errFile</b> on line <b>$errLine</b>";
            } else {
                $content = "<h1>500 - server error</h1>";
            }
            (new Response($content, 500))->send();
        }
    }

    protected static function logError(string $errStr, string $errFile, int $errLine, int $errNo): void
    {
        $logPath = static::getLogPath();
        // Log the error using Monolog
        $log = new Logger('error');
        $log->pushHandler(new StreamHandler($logPath, Level::Error));
        $log->error($errStr, [
             'file' => $errFile,
             'line' => $errLine,
             'code' => $errNo,
        ]);
    }

    protected static function renderException(Throwable $e): void
    {
        $class   = get_class($e);
        $debug   = app()->getConfig('debug');
        $message = $e->getMessage();
        $code    = $e->getCode();
        $trace   = $e->getTraceAsString();

        if (PHP_SAPI === 'cli') {
            echo "\033[31m Exception $class - $code - $message:\n $trace \033[0m";
        } elseif (method_exists($e, 'render')) {
            $e->render();
        } else {
            if ($debug) {
                $htmlErrorMessage = "<h1>Exception $class - $code - $message</h1>";
                $htmlErrorMessage .= "<p>File: {$e->getFile()}, Line: {$e->getLine()}</p>";
                $htmlErrorMessage .= "<pre>$trace</pre>";
                $content          = $htmlErrorMessage;
            } else {
                $content = "<h1>500 - server error</h1>";
            }
            (new Response($content, 500))->send();
        }
    }

}
