<?php

namespace Core\Http;

use Core\Contracts\Http\Response as ResponseContract;

class Response implements ResponseContract
{
    public function __construct(
         protected mixed $content = null,
         protected int $statusCode = 200,
         protected array $headers = []
    ) {
    }

    public function setHeader(string $header, mixed $value): static
    {
        $this->headers[$header] = $value;
        return $this;
    }

    public function setContent(mixed $content): static
    {
        $this->content = $content;
        return $this;
    }

    public function setStatusCode(int $statusCode): static
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function send(): void
    {
        // set the HTTP status code
        http_response_code($this->statusCode);
        // set the response headers
        foreach ($this->headers as $header => $value) {
            header($header . ': ' . $value);
        }

        $content = $this->content;
        // encode the response content if it's an array or object
        if (is_array($this->content) || is_object($this->content)) {
            $content = json_encode($this->content);
        }

        // send the response content
        echo $content;
    }

    public function redirect(string $url, int $statusCode = 302): static
    {
        $this->setHeader('Location', $url);
        $this->setStatusCode($statusCode);
        return $this;
    }

    public function withJson(array $data, int $statusCode = 200): static
    {
        $this->setHeader('Content-Type', 'application/json');
        $this->setContent($data);
        $this->setStatusCode($statusCode);
        return $this;
    }

    public function withHtml(string $html, int $statusCode = 200): static
    {
        $this->setHeader('Content-Type', 'text/html');
        $this->setContent($html);
        $this->setStatusCode($statusCode);
        return $this;
    }

    public function withView(string $view, array $data = [], int $statusCode = 200): static
    {
        $content = render_view($view, $data);
        $this->withHtml($content, $statusCode);
        return $this;
    }
}
