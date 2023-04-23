<?php

namespace Core\Contracts\Http;

interface Response
{
    public function setHeader(string $header, mixed $value): static;

    public function setContent(mixed $content): static;

    public function setStatusCode(int $statusCode): static;

    public function send(): void;

    public function redirect(string $url, int $statusCode = 302): static;

    public function withJson(array $data, int $statusCode = 200): static;

    public function withHtml(string $html, int $statusCode = 200): static;

    public function withView(string $view, array $data = [], int $statusCode = 200): static;
}
