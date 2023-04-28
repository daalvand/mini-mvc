<?php

namespace Tests\Unit\Core\Validator\Rules;

use Core\Contracts\Validator\Validator;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

use Core\Validator\Rules\Min;

class MinTest extends TestCase
{
    public function test_min_string(): void
    {
        $validator = $this->mockValidator();
        $validator->expects($this->never())->method('addError');
        $rule = new Min($validator, 'field', 'testing', [7]);
        $rule->validate();
        $this->assertTrue($rule->check());
    }

    public function test_min_string_failed(): void
    {
        $validator = $this->mockValidator();
        $validator->expects($this->once())
                  ->method('addError')
                  ->with('field', 'Min length of this field must be 8');
        $rule = new Min($validator, 'field', 'testing', [8]);
        $rule->validate();
        $this->assertFalse($rule->check());
        $this->assertEquals('Min length of this field must be 8', $rule->message());
    }

    public function test_min_array(): void
    {
        $validator = $this->mockValidator();
        $validator->expects($this->never())->method('addError');
        $rule = new Min($validator, 'field', [1, 2, 3, 4, 5, 6], [6]);
        $rule->validate();
        $this->assertTrue($rule->check());
    }

    public function test_min_array_failed(): void
    {
        $validator = $this->mockValidator();
        $validator->expects($this->once())
                  ->method('addError')
                  ->with('field', 'Min count of this field must be 7');
        $rule = new Min($validator, 'field', [1, 2, 3, 4, 5, 6], [7]);
        $rule->validate();
        $this->assertFalse($rule->check());
        $this->assertEquals('Min count of this field must be 7', $rule->message());
    }


    public function test_min_number(): void
    {
        $validator = $this->mockValidator();
        $validator->expects($this->never())->method('addError');
        $rule = new Min($validator, 'field', 5, [5]);
        $rule->validate();
        $this->assertTrue($rule->check());
    }

    public function test_min_number_failed(): void
    {
        $validator = $this->mockValidator();
        $validator->expects($this->once())
                  ->method('addError')
                  ->with('field', 'Min value of this field must be 9');
        $rule = new Min($validator, 'field', 8, [9]);
        $rule->validate();
        $this->assertFalse($rule->check());
        $this->assertEquals('Min value of this field must be 9', $rule->message());
    }

    private function mockValidator(): Validator&MockObject
    {
        return $this->getMockBuilder(Validator::class)->getMock();
    }
}

