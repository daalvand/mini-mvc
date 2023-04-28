<?php

namespace Tests\Unit\Validators;

use App\Validators\LoginValidator;
use Tests\TestCase;

class LoginValidatorTest extends TestCase
{
    protected LoginValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = LoginValidator::make();
    }

    public function test_valid_input(): void
    {
        $this->validator->validate([
             'email'    => 'test@example.com',
             'password' => 'password123',
        ]);
        $this->assertTrue($this->validator->passes());
        $this->assertFalse($this->validator->hasError('password'));
        $this->assertFalse($this->validator->hasError('email'));
        $this->assertFalse($this->validator->firstErrorOf('password'));
        $this->assertFalse($this->validator->firstErrorOf('email'));
    }

    public function test_invalid_input(): void
    {
        $this->validator->validate([
             'email'    => 'notanemail',
             'password' => '',
        ]);
        $this->assertFalse($this->validator->passes());
        $this->assertTrue($this->validator->hasError('email'));
        $this->assertTrue($this->validator->hasError('password'));
        $this->assertEquals('This field is required', $this->validator->firstErrorOf('password'));
        $this->assertEquals('This field must be valid email address', $this->validator->firstErrorOf('email'));

    }

    public function test_invalid_array_inputs(): void
    {
        $data = [
             'email'    => ['test'],
             'password' => ['test'],
        ];
        $this->validator->validate($data);


        // Assert
        $this->assertTrue($this->validator->hasError('email'));
        $this->assertTrue($this->validator->hasError('password'));
        $this->assertEquals('This field must be string', $this->validator->firstErrorOf('email'));
        $this->assertEquals('This field must be string', $this->validator->firstErrorOf('password'));
    }
}
