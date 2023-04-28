<?php

namespace Tests\Unit\Core\Validator\Rules;

use Core\Contracts\Validator\Validator;
use Core\Validator\Rules\Confirmed;
use PHPUnit\Framework\MockObject\Exception;
use Tests\TestCase;

class ConfirmedTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_check_returns_true_when_values_match(): void
    {
        $validator = $this->createMock(Validator::class);
        $validator->expects($this->never())->method('addError');
        $validator->method('getValueOf')->willReturn('password123');
        $rule = new Confirmed($validator, 'password', 'password123', ['password_confirmation']);
        $rule->validate();
        $this->assertTrue($rule->check());
    }

    /**
     * @throws Exception
     */
    public function test_check_returns_false_when_values_do_not_match(): void
    {
        $validator = $this->createMock(Validator::class);
        $validator->expects($this->once())
                  ->method('addError')
                  ->with('password', 'The password confirmation does not match.');
        $validator->method('getValueOf')->willReturn('password456');
        $rule = new Confirmed($validator, 'password', 'password123', ['password_confirmation']);
        $rule->validate();
        $this->assertFalse($rule->check());
    }

    /**
     * @throws Exception
     */
    public function test_message_returns_correct_message(): void
    {
        $validator = $this->createMock(Validator::class);
        $rule      = new Confirmed($validator, 'password', 'password123', ['password_confirmation']);
        $this->assertSame('The password confirmation does not match.', $rule->message());
    }
}
