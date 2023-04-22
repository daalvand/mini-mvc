<?php

namespace Core;

use Core\Contracts\DB\UserModel;
use Core\Contracts\Session;
use Core\Contracts\AuthManager as AuthManagerContract;

class AuthManager implements AuthManagerContract
{
    protected UserModel|null $user = null;
    /** @var UserModel */
    protected string $userClass;

    public function __construct(protected Session $session, protected array $configs)
    {
        $this->userClass = $configs['user'];
        $this->checkAuth();
    }

    public function checkAuth(): void
    {
        $userId = $this->session->get('user');
        if ($userId) {
            $this->user = $this->userClass::findOne($userId);
        }
    }

    public function getUser(): UserModel|null
    {
        return $this->user;
    }

    public function isGuest(): bool
    {
        return !$this->user;
    }

    public function login(UserModel $user): bool
    {
        $this->user = $user;
        $className  = get_class($user);
        $primaryKey = $className::primaryKey();
        $value      = $user->{$primaryKey};
        $this->session->set('user', $value);
        return true;
    }

    public function logout(): bool
    {
        $this->user = null;
        $this->session->remove('user');
        return true;
    }
}
