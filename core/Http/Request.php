<?php

namespace Core\Http;

use Core\Contracts\Http\Request as RequestContract;

class Request implements RequestContract
{
    protected array $routeParams = [];
    protected array $queryParams;
    protected array $bodyParams;
    protected array $cookies;
    protected array $files;
    protected array $server;
    protected array $headers;

    public function __construct(array $data = [])
    {
        $this->queryParams = $data['query'] ?? $_GET;
        $this->bodyParams  = $data['body'] ?? $_POST;
        $this->cookies     = $data['cookies'] ?? $_COOKIE;
        $this->files       = $data['files'] ?? $_FILES;
        $this->server      = $data['server'] ?? $_SERVER;
        $this->headers     = $data['headers'] ?? getallheaders();
    }

    public function all(): array
    {
        return array_merge($this->queryParams, $this->bodyParams);
    }

    public function method(): string
    {
        return strtolower($this->server['REQUEST_METHOD']);
    }

    public function url(): string
    {
        $path     = $this->server['REQUEST_URI'];
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        return $path;
    }

    public function isGet(): bool
    {
        return $this->method() === 'get';
    }

    public function isPost(): bool
    {
        return $this->method() === 'post';
    }

    public function body(): array
    {
        return $this->bodyParams;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->queryParams[$key] ?? $default;
    }

    public function post(string $key, mixed $default = null): mixed
    {
        return $this->bodyParams[$key] ?? $default;
    }

    public function setRouteParams(array $routeParams): void
    {
        $this->routeParams = $routeParams;
    }

    public function routeParams(): array
    {
        return $this->routeParams;
    }

    public function routeParam(string $key): mixed
    {
        return $this->routeParams[$key] ?? null;
    }

    public function cookies(): array
    {
        return $this->cookies;
    }

    public function cookie(string $key, mixed $default = null): mixed
    {
        return $this->cookies[$key] ?? $default;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function header(string $key, mixed $default = null): mixed
    {
        return $this->headers[$key] ?? $default;
    }

    public function isSecure(): bool
    {
        return isset($this->server['HTTPS']) && $this->server['HTTPS'] === 'on';
    }
}
