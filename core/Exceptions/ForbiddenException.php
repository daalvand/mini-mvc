<?php

namespace Core\Exceptions;

use Core\Http\Response;
use Exception;

class ForbiddenException extends Exception
{
    public function __construct(string $message = "Permission denied!", int $code = 403)
    {
        parent::__construct($message, $code);
    }

    public function render(): void
    {
        (new Response($this->message, $this->code))->send();
    }
}
