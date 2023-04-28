<?php

namespace Tests\Unit\Validators;

use App\Validators\RegisterValidator;
use Core\Contracts\DB\QueryBuilder as QueryBuilderContract;
use Tests\TestCase;

class RegisterValidatorTest extends TestCase
{

    private RegisterValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = RegisterValidator::make();
    }

    public function test_valid_input(): void
    {
        $this->validator->validate([
             'first_name'            => 'John',
             'last_name'             => 'Doe',
             'email'                 => 'john.doe@example.com',
             'password'              => 'Password123',
             'password_confirmation' => 'Password123',
        ]);
        $this->assertTrue($this->validator->passes());
        $this->assertFalse($this->validator->hasError('first_name'));
        $this->assertFalse($this->validator->hasError('last_name'));
        $this->assertFalse($this->validator->hasError('password'));
        $this->assertFalse($this->validator->hasError('password_confirmation'));
        $this->assertFalse($this->validator->hasError('email'));
        $this->assertFalse($this->validator->firstErrorOf('first_name'));
        $this->assertFalse($this->validator->firstErrorOf('last_name'));
        $this->assertFalse($this->validator->firstErrorOf('password'));
        $this->assertFalse($this->validator->firstErrorOf('password_confirmation'));
        $this->assertFalse($this->validator->firstErrorOf('email'));
    }

    public function test_invalid_input(): void
    {
        $this->validator->validate([
             'first_name'            => '',
             'last_name'             => '',
             'email'                 => 'invalid-email',
             'password'              => '',
             'password_confirmation' => '',
        ]);
        $this->assertFalse($this->validator->passes());
        $this->assertTrue($this->validator->hasError('first_name'));
        $this->assertTrue($this->validator->hasError('last_name'));
        $this->assertTrue($this->validator->hasError('password'));
        $this->assertTrue($this->validator->hasError('password_confirmation'));
        $this->assertTrue($this->validator->hasError('email'));
        $this->assertEquals('This field is required', $this->validator->firstErrorOf('first_name'));
        $this->assertEquals('This field is required', $this->validator->firstErrorOf('last_name'));
        $this->assertEquals('This field is required', $this->validator->firstErrorOf('password'));
        $this->assertEquals('This field is required', $this->validator->firstErrorOf('password_confirmation'));
        $this->assertEquals('This field must be valid email address', $this->validator->firstErrorOf('email'));

    }

    public function test_required_fields(): void
    {
        $this->validator->validate([
             'first_name'            => '',
             'last_name'             => '',
             'email'                 => '',
             'password'              => '',
             'password_confirmation' => '',
        ]);

        $this->assertFalse($this->validator->passes());
        $this->assertTrue($this->validator->hasError('first_name'));
        $this->assertTrue($this->validator->hasError('last_name'));
        $this->assertTrue($this->validator->hasError('email'));
        $this->assertTrue($this->validator->hasError('password'));
        $this->assertTrue($this->validator->hasError('password_confirmation'));
        $this->assertEquals('This field is required', $this->validator->firstErrorOf('first_name'));
        $this->assertEquals('This field is required', $this->validator->firstErrorOf('last_name'));
        $this->assertEquals('This field is required', $this->validator->firstErrorOf('email'));
        $this->assertEquals('This field is required', $this->validator->firstErrorOf('password'));
        $this->assertEquals('This field is required', $this->validator->firstErrorOf('password_confirmation'));
    }

    public function test_email_validation(): void
    {
        $this->validator->validate([
             'first_name'            => 'John',
             'last_name'             => 'Doe',
             'email'                 => 'invalid-email',
             'password'              => 'Password123',
             'password_confirmation' => 'Password123',
        ]);

        $this->assertFalse($this->validator->passes());
        $this->assertFalse($this->validator->hasError('first_name'));
        $this->assertFalse($this->validator->hasError('last_name'));
        $this->assertTrue($this->validator->hasError('email'));
        $this->assertFalse($this->validator->hasError('password'));
        $this->assertFalse($this->validator->hasError('password_confirmation'));
        $this->assertFalse($this->validator->firstErrorOf('first_name'));
        $this->assertFalse($this->validator->firstErrorOf('last_name'));
        $this->assertEquals('This field must be valid email address', $this->validator->firstErrorOf('email'));
        $this->assertFalse($this->validator->firstErrorOf('password'));
        $this->assertFalse($this->validator->firstErrorOf('password_confirmation'));
    }

    /**
     * @dataProvider invalidPasswordDataProvider
     */
    public function test_password_validation(string $password): void
    {
        $this->mockUniqueQueryBuilder();
        $this->validator->validate([
             'first_name'            => 'John',
             'last_name'             => 'Doe',
             'email'                 => 'johndoe@example.com',
             'password'              => $password,
             'password_confirmation' => $password,
        ]);
        $this->assertFalse($this->validator->passes());
        $this->assertEquals(
             'The password must contain at least one uppercase letter, one lowercase letter and one number and must be at least 8 characters long.',
             $this->validator->firstErrorOf('password')
        );
    }

    public function test_password_confirmation(): void
    {
        $this->mockUniqueQueryBuilder();
        $this->validator->validate([
             'first_name'            => 'John',
             'last_name'             => 'Doe',
             'email'                 => 'johndoe@example.com',
             'password'              => 'Password1234',
             'password_confirmation' => 'Password123',
        ]);

        $this->assertFalse($this->validator->passes());
        $this->assertEquals("The password confirmation does not match.", $this->validator->firstErrorOf('password'));
    }

    public function test_unique_email(): void
    {
        $this->mockUniqueQueryBuilder(true);
        $this->validator->validate([
             'first_name'            => 'John',
             'last_name'             => 'Doe',
             'email'                 => 'johndoe@example.com',
             'password'              => 'Password123',
             'password_confirmation' => 'Password123',
        ]);

        $this->assertFalse($this->validator->passes());
        $this->assertEquals(
             "Record with this value: johndoe@example.com already exists",
             $this->validator->firstErrorOf('email')
        );
    }

    protected function mockUniqueQueryBuilder(bool $return = false): void
    {
        $this->app->bind(QueryBuilderContract::class, function () use ($return) {
            $mock = $this->getMockBuilder(QueryBuilderContract::class)->getMock();
            $mock->method('table')->willReturn($mock);
            $mock->method('where')->willReturn($mock);
            $mock->method('exists')->willReturn($return);
            return $mock;
        });
    }

    public static function invalidPasswordDataProvider(): array
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
