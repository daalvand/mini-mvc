<?php

namespace Core\Contracts;

interface View
{
    public function render(string $view, array $data = []): bool|string;

    public function clearCache(): void;
}
