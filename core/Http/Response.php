<?php

namespace Core\Http;

use Core\Contracts\Http\Response as ResponseContract;
use Core\Contracts\View;

class Response implements ResponseContract
{
    public function __construct(protected View $view)
    {
    }

    public function statusCode(int $status): static
    {
        http_response_code($status);
        return $this;
    }

    public function redirect($url): void
    {
        header("Location: $url");
    }

    public function render(string $view, array $data = []): string
    {
        return $this->view->view($view, $data);
    }
}