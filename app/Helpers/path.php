<?php


if (!function_exists('merge_paths')) {
    function merge_paths(string ...$paths): string
    {
        foreach ($paths as $key => $path) {
            $paths[$key] = match ($key) {
                0        => rtrim($path, '/ '),
                default  => trim($path, '/ '),
            };
        }
        return implode('/', $paths);
    }
}
