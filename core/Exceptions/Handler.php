<?php

namespace Core\Exceptions;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Throwable;

class Handler
{
    public static function errorHandler(int $errNo, string $errStr, string $errFile, int $errLine, array $errContext): never
    {
        $logPath = 'storage/logs/' . date('Y-m-d') . '.log';
        // Log the error using Monolog
        $log = new Logger('error');
        $log->pushHandler(new StreamHandler($logPath, Level::Error));
        $log->error($errStr, [
            'file' => $errFile,
            'line' => $errLine,
            'context' => json_encode($errContext),
            'code' => $errNo,
        ]);
        echo "<h1>500 - server error</h1>";
        exit;
    }

    public static function exceptionHandler(Throwable $e): never
    {
        http_response_code(500);
        if ($e instanceof NotFoundException) {
            http_response_code(404);
            echo '<h1>Not Found Error</h1>';
        } elseif ($e instanceof ForbiddenException) {
            http_response_code(403);
            echo '<h1>You doo\'t have permission on this page!</h1>';
        } else {
            $logPath = 'storage/logs/' . date('Y-m-d') . '.log';
            $log = new Logger('app');
            $log->pushHandler(new StreamHandler($logPath, Level::Warning));
            $log->error('ERROR: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => json_encode($e->getTrace()),
                'code' => $e->getCode(),
                'exception' => get_class($e),
            ]);
            echo "<h1>500 - server error</h1>";
        }
        exit;
    }
}
