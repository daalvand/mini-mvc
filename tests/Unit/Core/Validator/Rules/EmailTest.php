<?php

namespace Tests\Unit\Core\Validator\Rules;

use Core\Contracts\Validator\Validator;
use Core\Validator\Rules\Email;
use PHPUnit\Framework\MockObject\Exception;
use Tests\TestCase;

class EmailTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_check_returns_true_for_valid_email(): void
    {
        $validator = $this->createMock(Validator::class);
        $validator->expects($this->never())->method('addError');
        $email = new Email($validator, 'email', 'test@example.com', []);
        $email->validate();
        $this->assertTrue($email->check());
    }

    /**
     * @throws Exception
     */
    public function test_check_returns_false_for_invalid_email(): void
    {
        $validator = $this->createMock(Validator::class);
        $validator->expects($this->once())
                  ->method('addError')
                  ->with('email', 'This field must be a valid email address');
        $email = new Email($validator, 'email', 'testexample.com', []);
        $email->validate();
        $this->assertFalse($email->check());
    }

    /**
     * @throws Exception
     */
    public function test_message_returns_expected_value(): void
    {
        $validator = $this->createMock(Validator::class);
        $email     = new Email($validator, 'email', 'testexample.com', []);
        $this->assertEquals('This field must be a valid email address', $email->message());
    }
}
