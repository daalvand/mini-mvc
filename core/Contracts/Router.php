<?php

namespace Core\Contracts;

interface Router
{
    public function get(
         string $path,
         array|string|callable $callback,
         array $conditions = [],
         array $middlewares = []
    ): void;

    public function post(
         string $path,
         array|string|callable $callback,
         array $conditions = [],
         array $middlewares = []
    ): void;

    public function put(
         string $path,
         array|string|callable $callback,
         array $conditions = [],
         array $middlewares = []
    ): void;

    public function resolve(): mixed;
}
