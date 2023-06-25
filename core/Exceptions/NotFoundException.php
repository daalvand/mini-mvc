<?php

namespace Core\Exceptions;

use Core\Http\Response;
use Exception;

class NotFoundException extends Exception
{
    public function __construct(string $message = 'Page not found', int $code = 404)
    {
        parent::__construct($message, $code);
    }

    public function render(): void
    {
        (new Response($this->message, $this->code))->send();
    }
}
