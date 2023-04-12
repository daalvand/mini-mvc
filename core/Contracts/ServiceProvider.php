<?php

namespace Core\Contracts;

interface ServiceProvider
{
    public function register(): void;
}