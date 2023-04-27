<?php

namespace Tests\Unit\Validators;

use App\Validators\LoginValidator;
use Tests\TestCase;

class LoginValidatorTest extends TestCase
{
    public function testValidateReturnsTrueWhenValidDataIsProvided(): void
    {
        // Arrange
        $validator = new LoginValidator();
        $data      = [
             'email'    => 'test@example.com',
             'password' => 'password123',
        ];
        $validator->loadData($data);

        // Act
        $result = $validator->validate();

        // Assert
        $this->assertEquals($data, $result);
    }

    public function testValidateReturnsFalseWhenInvalidDataIsProvided(): void
    {
        // Arrange
        $validator = new LoginValidator();
        $data      = [
             'email'    => 'notanemail',
             'password' => '',
        ];
        $validator->loadData($data);

        // Act
        $result = $validator->validate();

        // Assert
        $this->assertFalse($result);
    }

    public function testHasErrorReturnsTrueWhenThereIsAnError(): void
    {
        // Arrange
        $validator = new LoginValidator();
        $data      = [
             'email'    => 'notanemail',
             'password' => ['test'],
        ];
        $validator->loadData($data);

        // Act
        $validator->validate();

        // Assert
        $this->assertEquals('This field must be valid email address', $validator->firstErrorOf('email'));
        $this->assertEquals('This field must be string', $validator->firstErrorOf('password'));
    }

    public function testHasErrorReturnsFalseWhenThereIsNoError(): void
    {
        // Arrange
        $validator = new LoginValidator();
        $data      = [
             'email'    => 'test@example.com',
             'password' => 'password123',
        ];
        $validator->loadData($data);

        // Act
        $validator->validate();
        $result = $validator->hasError('email');

        // Assert
        $this->assertFalse($result);
    }

    public function testFirstErrorOfReturnsAnEmptyStringWhenThereIsNoError(): void
    {
        // Arrange
        $validator = new LoginValidator();
        $data      = [
             'email'    => 'test@example.com',
             'password' => 'password123',
        ];
        $validator->loadData($data);

        // Act
        $validator->validate();
        $result = $validator->firstErrorOf('email');

        // Assert
        $this->assertEquals('', $result);
    }
}
