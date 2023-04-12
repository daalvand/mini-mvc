<?php

namespace Core\Contracts;

interface App
{
    public function run(): void;

    public function set(string $contract, callable|object $callable): void;

    public function get(string $contract): mixed;

    public static function getInstance(): static;
}