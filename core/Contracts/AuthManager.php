<?php

namespace Core\Contracts;

use Core\DB\UserModel;

interface AuthManager
{
    public function checkAuth(): void;

    public function getUser(): UserModel|null;

    public function isGuest(): bool;

    public function login(UserModel $user): bool;

    public function logout(): bool;
}
