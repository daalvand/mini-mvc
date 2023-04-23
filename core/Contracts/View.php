<?php

namespace Core\Contracts;

interface View
{
    public function view(string $view, array $data = []): bool|string;

    public function clearCache(): void;

    public function share(string $string, string $token): void;
}
