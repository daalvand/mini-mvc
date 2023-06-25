<?php

namespace Tests\Unit\Core\Validator\Rules;

use Core\Contracts\Validator\Validator;
use Core\Validator\Rules\Confirmed;
use Core\Validator\Rules\Password;
use PHPUnit\Framework\MockObject\Exception;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_valid_password(): void
    {
        $validator = $this->createMock(Validator::class);
        $validator->expects($this->never())->method('addError');
        $rule = new Password($validator, 'password', 'Abc12345', []);
        $rule->validate();
        $this->assertTrue($rule->check());
    }

    /**
     * @dataProvider invalidPasswordProvider
     * @throws Exception
     */
    public function test_invalid_password(): void
    {
        $validator = $this->createMock(Validator::class);
        $validator->expects($this->once())->method('addError')->with('password', $this->getMessage());
        $rule = new Password($validator, 'password', 'Abc1234', []);
        $rule->validate();
        $this->assertFalse($rule->check());
    }

    private function getMessage(): string
    {
        return 'The password must contain at least one uppercase letter, one lowercase letter and one number and must be at least 8 characters long.';
    }

    public static function invalidPasswordProvider(): array
    {
        return [
             ['password'],
             ['Password'],
             ['PASSWORD'],
             ['123456'],
             ['password123'],
             ['PASSWORD123'],
             ['Password'],
             ['Pass'],
        ];
    }
}
