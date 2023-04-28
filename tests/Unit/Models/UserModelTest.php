<?php

namespace Tests\Unit\Models;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserModelTest extends TestCase
{
    /**
     * @dataProvider itemDataProvider
     */
    public function test_full_name(User $user): void
    {
        $this->assertEquals('John Doe', $user->fullName());
    }

    public function test_table_name(): void
    {
        $this->assertEquals('users', User::tableName());
    }

    /**
     * @dataProvider itemDataProvider
     */
    public function test_fillable(User $user): void
    {
        $this->assertEquals(['first_name', 'last_name', 'email', 'password'], $user->getFillable());
    }

    /**
     * @dataProvider itemDataProvider
     */
    public function test_properties(User $user): void
    {
        $this->assertEquals('John', $user->first_name);
        $this->assertEquals('Doe', $user->last_name);
        $this->assertEquals('john.doe@example.com', $user->email);
        $this->assertEquals('password123', $user->password);
    }

    //item dataProvider
    public static function itemDataProvider(): array
    {
        return [
             [
                  new User([
                       'first_name' => 'John',
                       'last_name'  => 'Doe',
                       'email'      => 'john.doe@example.com',
                       'password'   => 'password123',
                  ]),
             ],
        ];
    }
}
