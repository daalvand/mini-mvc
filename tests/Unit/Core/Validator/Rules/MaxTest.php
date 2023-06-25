<?php

namespace Tests\Unit\Core\Validator\Rules;

use Core\Contracts\Validator\Validator;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

use Core\Validator\Rules\Max;

class MaxTest extends TestCase
{
    public function test_max_string(): void
    {
        $validator = $this->mockValidator();
        $validator->expects($this->never())->method('addError');
        $rule = new Max($validator, 'field', 'testing', [7]);
        $rule->validate();
        $this->assertTrue($rule->check());
    }

    public function test_max_string_failed(): void
    {
        $validator = $this->mockValidator();
        $validator->expects($this->once())
                  ->method('addError')
                  ->with('field', 'Max length of this field must be 6');
        $rule = new Max($validator, 'field', 'testing', [6]);
        $rule->validate();
        $this->assertFalse($rule->check());
        $this->assertEquals('Max length of this field must be 6', $rule->message());
    }

    public function test_max_array(): void
    {
        $validator = $this->mockValidator();
        $validator->expects($this->never())->method('addError');
        $rule = new Max($validator, 'field', [1, 2, 3, 4, 5, 6], [7]);
        $this->assertTrue($rule->check());
    }

    public function test_max_array_failed(): void
    {
        $validator = $this->mockValidator();
        $validator->expects($this->once())
                  ->method('addError')
                  ->with('field', 'Max count of this field must be 5');
        $rule = new Max($validator, 'field', [1, 2, 3, 4, 5, 6], [5]);
        $rule->validate();
        $this->assertFalse($rule->check());
        $this->assertEquals('Max count of this field must be 5', $rule->message());
    }


    public function test_max_number(): void
    {
        $validator = $this->mockValidator();
        $validator->expects($this->never())->method('addError');
        $rule = new Max($validator, 'field', 5, [7]);
        $rule->validate();
        $this->assertTrue($rule->check());
    }

    public function test_max_number_failed(): void
    {
        $validator = $this->mockValidator();
        $validator->expects($this->once())
                  ->method('addError')
                  ->with('field', 'Max value of this field must be 7');
        $rule = new Max($validator, 'field', 8, [7]);
        $rule->validate();
        $this->assertFalse($rule->check());
        $this->assertEquals('Max value of this field must be 7', $rule->message());
    }

    private function mockValidator(): Validator&MockObject
    {
        return $this->getMockBuilder(Validator::class)->getMock();
    }
}

