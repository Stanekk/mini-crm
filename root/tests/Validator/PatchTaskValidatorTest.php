<?php

namespace App\Tests\Validator;

use App\Validator\PatchTaskValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PatchTaskValidatorTest extends TestCase
{
    private ValidatorInterface $validator;

    public function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->addMethodMapping('loadValidatorMetadata')
            ->getValidator();
    }

    public function testBlankNameIsInvalid(): void
    {
        $data = [
            'name' => '   ',
        ];

        $errors = $this->validator->validate(new PatchTaskValidator($data));
        $this->assertCount(1, $errors);
        $this->assertEquals('data.name', $errors[0]->getPropertyPath());
        $this->assertEquals('Name cannot be blank.', $errors[0]->getMessage());
    }

    public function testDueDateIsInvalid(): void
    {
        $data = [
            'dueDate' => '12 132',
        ];

        $errors = $this->validator->validate(new PatchTaskValidator($data));
        $this->assertCount(1, $errors);
        $this->assertEquals('data.dueDate', $errors[0]->getPropertyPath());
        $this->assertEquals('Due date is not valid.', $errors[0]->getMessage());
    }

    public function testStatusIsInvalid(): void
    {
        $data = [
            'status' => 'new status',
        ];

        $errors = $this->validator->validate(new PatchTaskValidator($data));
        $this->assertCount(1, $errors);
        $this->assertEquals('data.status', $errors[0]->getPropertyPath());
        $this->assertEquals('Invalid task status.', $errors[0]->getMessage());
    }

    public function testAssignedToIsInvalid(): void
    {
        $data = [
            'assignedTo' => '123',
        ];

        $errors = $this->validator->validate(new PatchTaskValidator($data));
        $this->assertCount(1, $errors);
        $this->assertEquals('data.assignedTo', $errors[0]->getPropertyPath());
        $this->assertEquals('Invalid assigned to id.', $errors[0]->getMessage());
    }
}
