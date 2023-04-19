<?php

namespace Core\Contracts;

use Exception;

interface App
{
    public function run(): void;

    public function singleton(string $contract, callable|object|array $callable): void;

    public function bind(string $contract, callable|object|array $callable): void;

    public function get(string $contract): mixed;

    public function getConfigs(): array;

    public function getConfig(string $key): mixed;

    public function basePath(): string;
}
