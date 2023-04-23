<?php

namespace Core\Contracts;

interface Session
{
    /**
     * temp data for something like flash messages or csrf token and so on
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function setTemp(string $key, mixed $value): void;

    public function getTemp(string $key): mixed;

    public function set(string $key, mixed $value, int $expireTime = null): void;

    public function get(string $key): mixed;

    public function remove(string $key): void;

    public function csrfToken(): string;
}
