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


//remove directory recursively
if (!function_exists('remove_directory')) {
    function remove_directory(string $directory): void
    {
        if (is_dir($directory)) {
            $files = glob($directory . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                } else {
                    remove_directory($file);
                }
            }
            rmdir($directory);
        }
    }
}
