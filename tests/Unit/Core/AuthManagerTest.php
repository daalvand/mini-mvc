<?php

namespace Tests\Unit\Core;

use App\Models\User;
use Core\AuthManager;
use Core\Contracts\Session;
use Core\DB\ModelQueryBuilder;
use PHPUnit\Framework\MockObject\Exception;
use Tests\TestCase;

class AuthManagerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_check_auth(): void
    {
        $session = $this->createMock(Session::class);
        $session->method('get')->willReturn(1);
        $user = new User([
             'id'         => 1,
             'first_name' => 'John',
             'last_name'  => 'Doe',
             'email'      => 'test@example.com',
        ]);

        $queryBuilder = $this->createMock(ModelQueryBuilder::class);
        $queryBuilder->expects($this->once())->method('model')->willReturn($queryBuilder);
        $queryBuilder->expects($this->once())->method('where')->willReturn($queryBuilder);
        $queryBuilder->expects($this->once())->method('first')->willReturn($user);
        $this->app->bind(ModelQueryBuilder::class, function () use ($queryBuilder) {
            return $queryBuilder;
        });

        $authManager = new AuthManager($session, ['user' => User::class]);
        $authManager->checkAuth();
        $this->assertEquals($user, $authManager->getUser());
    }

    /**
     * @throws Exception
     */
    public function test_login(): void
    {
        $session = $this->createMock(Session::class);
        $user    = new User([
             'id'         => 1,
             'first_name' => 'John',
             'last_name'  => 'Doe',
             'email'      => 'test@example.com',
        ]);

        $authManager = new AuthManager($session, ['user' => User::class]);
        $authManager->login($user);

        $this->assertEquals($user, $authManager->getUser());
    }

    /**
     * @throws Exception
     */
    public function test_is_guest(): void
    {
        $session = $this->createMock(Session::class);
        $user    = new User([
             'id'         => 1,
             'first_name' => 'John',
             'last_name'  => 'Doe',
             'email'      => 'test@example.com',
        ]);

        $authManager = new AuthManager($session, ['user' => get_class($user)]);
        $this->assertTrue($authManager->isGuest());
        $authManager->login($user);
        $this->assertFalse($authManager->isGuest());
    }

    /**
     * @throws Exception
     */
    public function test_logout(): void
    {
        $session = $this->createMock(Session::class);

        $user = new User([
             'id'         => 1,
             'first_name' => 'John',
             'last_name'  => 'Doe',
             'email'      => 'test@example.com',
        ]);

        $authManager = new AuthManager($session, ['user' => get_class($user)]);
        $authManager->login($user);
        $this->assertEquals($user, $authManager->getUser());

        $result = $authManager->logout();

        $this->assertTrue($result);
        $this->assertTrue($authManager->isGuest());
    }
}
