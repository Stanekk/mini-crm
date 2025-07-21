<?php

namespace App\Tests\Mapper;

use App\Entity\Task;
use App\Enum\TaskStatus;
use App\Mapper\ClientMapper;
use App\Mapper\CompanyMapper;
use App\Mapper\TaskMapper;
use App\Mapper\UserMapper;
use PHPUnit\Framework\TestCase;

class TaskMapperTest extends TestCase
{
    private TaskMapper $taskMapper;
    private ClientMapper $clientMapper;
    private CompanyMapper $companyMapper;
    private UserMapper $userMapper;

    public function setUp(): void
    {
        $this->companyMapper = new CompanyMapper();
        $this->clientMapper = new ClientMapper($this->companyMapper);
        $this->userMapper = new UserMapper();
        $this->taskMapper = new TaskMapper($this->userMapper, $this->clientMapper, $this->companyMapper);
    }

    public function testDoDtoWithoutClientAndCompanyAndUnassigned(): void
    {
        $task = new Task();
        $reflection = new \ReflectionClass(Task::class);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($task, 123);

        $task->setName('Task name');
        $task->setDescription('Task description');
        $task->setCreatedAt(new \DateTimeImmutable('2025-07-19 11:00:00'));
        $task->setUpdatedAt(new \DateTimeImmutable('2025-07-24 13:00:00'));
        $task->setDueDate(new \DateTimeImmutable('2025-07-26 12:00:00'));
        $task->setClient(null);
        $task->setCompany(null);
        $task->setAssignedTo(null);
        $task->setStatus(TaskStatus::InProgress);

        $dto = $this->taskMapper->toDto($task);

        $this->assertEquals(123, $dto->id);
        $this->assertEquals('Task name', $dto->name);
        $this->assertEquals('Task description', $dto->description);
        $this->assertEquals(TaskStatus::InProgress, $dto->status);
        $this->assertEquals(new \DateTimeImmutable('2025-07-19 11:00:00'), $dto->createdAt);
        $this->assertEquals(new \DateTimeImmutable('2025-07-24 13:00:00'), $dto->updatedAt);
        $this->assertEquals(new \DateTimeImmutable('2025-07-26 12:00:00'), $dto->dueDate);
        $this->assertNull($dto->company);
        $this->assertNull($dto->client);
        $this->assertNull($dto->assignedTo);
    }
}
