<?php

namespace App\Tests\Mapper;

use App\Dto\Client\ClientDto;
use App\Dto\Company\CompanyShortDto;
use App\Entity\Client;
use App\Entity\Company;
use App\Entity\Task;
use App\Enum\DataSource;
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
        $this->userMapper = $this->createMock(UserMapper::class);
        $this->clientMapper = $this->createMock(ClientMapper::class);
        $this->companyMapper = $this->createMock(CompanyMapper::class);
        $this->taskMapper = new TaskMapper($this->userMapper, $this->clientMapper, $this->companyMapper);
    }

    private function createBasicTask(): Task
    {
        $task = new Task();

        $reflection = new \ReflectionClass(Task::class);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($task, 123);

        $task->setName('Basic task name');
        $task->setDescription('Basic task description');
        $task->setCreatedAt(new \DateTimeImmutable('2025-07-19 11:00:00'));
        $task->setUpdatedAt(new \DateTimeImmutable('2025-07-24 13:00:00'));
        $task->setDueDate(new \DateTimeImmutable('2025-07-26 12:00:00'));

        return $task;
    }

    private function assertBasicTaskDtoProperties($dto): void
    {
        $this->assertSame(123, $dto->id);
        $this->assertSame('Basic task name', $dto->name);
        $this->assertSame('Basic task description', $dto->description);
        $this->assertEquals(new \DateTimeImmutable('2025-07-19 11:00:00'), $dto->createdAt);
        $this->assertEquals(new \DateTimeImmutable('2025-07-24 13:00:00'), $dto->updatedAt);
        $this->assertEquals(new \DateTimeImmutable('2025-07-26 12:00:00'), $dto->dueDate);
    }

    public function testToDtoWithoutClientAndCompanyAndUnassigned(): void
    {
        $task = $this->createBasicTask();
        $task->setClient(null);
        $task->setCompany(null);
        $task->setAssignedTo(null);
        $task->setStatus(TaskStatus::InProgress);

        $dto = $this->taskMapper->toDto($task);

        $this->assertBasicTaskDtoProperties($dto);
        $this->assertSame(TaskStatus::InProgress, $dto->status);
        $this->assertNull($dto->client);
        $this->assertNull($dto->company);
        $this->assertNull($dto->assignedTo);
    }

    public function testToDtoWithCompanyOnly(): void
    {
        $task = $this->createBasicTask();
        $task->setClient(null);
        $task->setAssignedTo(null);
        $task->setStatus(TaskStatus::InProgress);

        $company = $this->createMock(Company::class);
        $task->setCompany($company);

        $companyDto = new CompanyShortDto(
            id: 99,
            name: 'Apple',
            email: 'contact@apple.com',
            source: DataSource::Faker
        );

        $this->companyMapper
            ->method('toShortDto')
            ->with($company)
            ->willReturn($companyDto);

        $dto = $this->taskMapper->toDto($task);

        $this->assertBasicTaskDtoProperties($dto);

        $this->assertSame(TaskStatus::InProgress, $dto->status);

        $this->assertSame($companyDto, $dto->company);
        $this->assertNull($dto->client);
        $this->assertNull($dto->assignedTo);
    }

    public function testToDtoWithClientOnly(): void
    {
        $task = $this->createBasicTask();
        $task->setCompany(null);
        $task->setAssignedTo(null);
        $task->setStatus(TaskStatus::Pending);

        $client = $this->createMock(Client::class);
        $task->setClient($client);

        $clientDto = new ClientDto(
            id: 22,
            firstName: 'Anna',
            lastName: 'Nowak',
            company: null,
            email: 'anna@example.com',
            phone: '12345678',
        );

        $this->clientMapper
            ->method('toDto')
            ->with($client)
            ->willReturn($clientDto);

        $dto = $this->taskMapper->toDto($task);

        $this->assertBasicTaskDtoProperties($dto);

        $this->assertSame(TaskStatus::Pending, $dto->status);

        $this->assertSame($clientDto, $dto->client);
        $this->assertNull($dto->company);
        $this->assertNull($dto->assignedTo);
    }
}
