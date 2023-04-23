<?php

namespace Core\Contracts\Http;

interface Request
{
    public function getMethod(): string;

    public function getUrl(): string;

    public function isGet(): bool;

    public function isPost(): bool;

    public function getBody(): array;

    public function get(string $key, mixed $default = null): mixed;

    public function post(string $key, mixed $default = null): mixed;

    public function setRouteParams(array $routeParams): void;

    public function getRouteParams(): array;

    public function getRouteParam(string $key);

    public function getCookies(): array;

     public function getCookie(string $key, mixed $default = null): mixed;

     public function getHeaders(): array;

     public function getHeader(string $key, mixed $default = null): mixed;
}
