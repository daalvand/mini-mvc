<?php

namespace Tests\Unit\Core\Validator\Rules;

use Core\Contracts\Validator\Validator;
use Core\Validator\Rules\Required;
use PHPUnit\Framework\MockObject\Exception;
use Tests\TestCase;

class RequiredTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_check_returns_true_for_non_empty_value(): void
    {
        $validator = $this->createMock(Validator::class);
        $validator->expects($this->never())->method('addError');
        $required = new Required($validator, 'name', 'John', []);
        $required->validate();
        $this->assertTrue($required->check());
    }

    /**
     * @throws Exception
     */
    public function test_check_returns_false_for_empty_value(): void
    {
        $validator = $this->createMock(Validator::class);
        $validator->expects($this->once())
                  ->method('addError')
                  ->with('name', 'This field is required');
        $required = new Required($validator, 'name', '', []);
        $required->validate();
        $this->assertFalse($required->check());
    }

    /**
     * @throws Exception
     */
    public function test_message_returns_expected_value(): void
    {
        $validator = $this->createMock(Validator::class);
        $required  = new Required($validator, 'name', '', []);
        $this->assertEquals('This field is required', $required->message());
    }
}
