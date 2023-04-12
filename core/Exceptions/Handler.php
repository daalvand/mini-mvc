<?php

namespace Core\Exceptions;

use ErrorException;
use Throwable;

class Handler
{

    /**
     * Error handler. Convert all errors to Exceptions by throwing an ErrorException.
     *
     * @param int    $level   Error level
     * @param string $message Error message
     * @param string $file    Filename the error raised in
     * @param int    $line    Line number in the file
     *
     * @return void
     * @throws ErrorException
     */
    public static function errorHandler(int $level, string $message, string $file, int $line): void
    {
        if (error_reporting() !== 0) {  // to keep the @ operator working
            throw new ErrorException($message, 0, $level, $file, $line);
        }
    }

    public static function exceptionHandler(Throwable $e): void
    {
        http_response_code(500);
        if ($e instanceof NotFoundException) {
            http_response_code(404);
            echo '<h1>Not Found Error</h1>';
        } elseif ($e instanceof ForbiddenException) {
            http_response_code(403);
            echo '<h1>You doo\'t have permission on this page!</h1>';
        } else {
            $log = 'storage/logs/' . date('Y-m-d') . '.log';
            ini_set('error_log', $log);
            $message = "Uncaught exception: '" . get_class($e) . "'";
            $message .= " with message '" . $e->getMessage() . "'";
            $message .= "\nStack trace: " . $e->getTraceAsString();
            $message .= "\nThrown in '" . $e->getFile() . "' on line " . $e->getLine();
            error_log($message);
            echo "<h1>500 - server error</h1>";
        }
    }
}
