<?php

namespace Core\Contracts\Http;

interface Request
{
    public function all(): array;

    public function method(): string;

    public function url(): string;

    public function isGet(): bool;

    public function isPost(): bool;

    public function body(): array;

    public function get(string $key, mixed $default = null): mixed;

    public function post(string $key, mixed $default = null): mixed;

    public function setRouteParams(array $routeParams): void;

    public function routeParams(): array;

    public function routeParam(string $key): mixed;

    public function cookies(): array;

    public function cookie(string $key, mixed $default = null): mixed;

    public function headers(): array;

    public function header(string $key, mixed $default = null): mixed;

    public function isSecure(): bool;
}
