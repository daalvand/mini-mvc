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
        $this->validator = new RegisterValidator();
    }

    public function testValidInput(): void
    {
        $this->validator->loadData([
             'first_name'            => 'John',
             'last_name'             => 'Doe',
             'email'                 => 'john.doe@example.com',
             'password'              => 'Password123',
             'password_confirmation' => 'Password123',
        ]);
        $this->validator->validate();
        $this->assertTrue($this->validator->passes());
    }

    public function testInvalidInput(): void
    {
        $this->validator->loadData([
             'first_name'            => '',
             'last_name'             => '',
             'email'                 => 'invalid-email',
             'password'              => '',
             'password_confirmation' => '',
        ])->validate();
        $this->assertFalse($this->validator->passes());
        $this->assertEquals('This field is required', $this->validator->firstErrorOf('first_name'));
        $this->assertEquals('This field is required', $this->validator->firstErrorOf('last_name'));
        $this->assertEquals('This field is required', $this->validator->firstErrorOf('password'));
        $this->assertEquals('This field is required', $this->validator->firstErrorOf('password_confirmation'));
        $this->assertEquals('This field must be valid email address', $this->validator->firstErrorOf('email'));

    }

    public function testRequiredFields(): void
    {
        $this->validator->loadData([
             'first_name'            => '',
             'last_name'             => '',
             'email'                 => '',
             'password'              => '',
             'password_confirmation' => '',
        ]);
        $this->validator->validate();

        $this->assertFalse($this->validator->passes());
        $this->assertEquals('This field is required', $this->validator->firstErrorOf('first_name'));
        $this->assertEquals('This field is required', $this->validator->firstErrorOf('last_name'));
        $this->assertEquals('This field is required', $this->validator->firstErrorOf('email'));
        $this->assertEquals('This field is required', $this->validator->firstErrorOf('password'));
        $this->assertEquals('This field is required', $this->validator->firstErrorOf('password_confirmation'));
    }

    public function testEmailValidation(): void
    {
        $this->validator->loadData([
             'first_name'            => 'John',
             'last_name'             => 'Doe',
             'email'                 => 'invalid-email',
             'password'              => 'Password123',
             'password_confirmation' => 'Password123',
        ]);
        $this->validator->validate();

        $this->assertFalse($this->validator->passes());
        $this->assertEquals('This field must be valid email address', $this->validator->firstErrorOf('email'));
    }

    /**
     * @dataProvider invalidPasswordDataProvider
     */
    public function testPasswordValidation(string $password): void
    {
        $this->mockUniqueQueryBuilder();
        $this->validator->loadData([
             'first_name'            => 'John',
             'last_name'             => 'Doe',
             'email'                 => 'johndoe@example.com',
             'password'              => $password,
             'password_confirmation' => $password,
        ]);
        $this->validator->validate();
        $this->assertFalse($this->validator->passes());
        $this->assertEquals(
             'The password must contain at least one uppercase letter, one lowercase letter and one number and must be at least 8 characters long.',
             $this->validator->firstErrorOf('password')
        );
    }

    public function testPasswordConfirmation(): void
    {
        $this->mockUniqueQueryBuilder();
        $this->validator->loadData([
             'first_name'            => 'John',
             'last_name'             => 'Doe',
             'email'                 => 'johndoe@example.com',
             'password'              => 'Password1234',
             'password_confirmation' => 'Password123',
        ])->validate();

        $this->assertFalse($this->validator->passes());
        $this->assertEquals("The password confirmation does not match.", $this->validator->firstErrorOf('password'));
    }

    public function testUniqueEmail(): void
    {
        $this->mockUniqueQueryBuilder(true);
        $this->validator->loadData([
             'first_name'            => 'John',
             'last_name'             => 'Doe',
             'email'                 => 'johndoe@example.com',
             'password'              => 'Password123',
             'password_confirmation' => 'Password123',
        ]);
        $this->validator->validate();

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
