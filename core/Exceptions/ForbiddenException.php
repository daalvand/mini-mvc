<?php

namespace Core\Exceptions;

use Exception;

class ForbiddenException extends Exception
{
    public function __construct(string $message = "Permission denied!", int $code = 403)
    {
        parent::__construct($message, $code);
    }
}
