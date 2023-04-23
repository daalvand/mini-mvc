<?php

namespace Core\Http;

use Core\Contracts\Http\Request as RequestContract;

class Request implements RequestContract
{
    private array $routeParams = [];

    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getUrl(): string
    {
        $path     = $_SERVER['REQUEST_URI'];
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        return $path;
    }

    public function isGet(): bool
    {
        return $this->getMethod() === 'get';
    }

    public function isPost(): bool
    {
        return $this->getMethod() === 'post';
    }

    public function getBody(): array
    {
        $data = [];
        if ($this->isGet()) {
            $data = $_GET;
        }
        if ($this->isPost()) {
            $data = $_POST;
        }
        return $data;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    public function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    public function setRouteParams(array $routeParams): void
    {
        $this->routeParams = $routeParams;
    }

    public function getRouteParams(): array
    {
        return $this->routeParams;
    }

    public function getRouteParam(string $key)
    {
        return $this->routeParams[$key] ?? null;
    }

    public function getCookies(): array
    {
        return $_COOKIE;
    }

    public function getCookie(string $key, mixed $default = null): mixed
    {
        return $_COOKIE[$key] ?? $default;
    }

    public function getHeaders(): array
    {
        return getallheaders();
    }

    public function getHeader(string $key, mixed $default = null): mixed
    {
        return getallheaders()[$key] ?? $default;
    }
}
