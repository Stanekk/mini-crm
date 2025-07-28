<?php

namespace App\Tests\Service;

use App\Dto\Task\CreateTaskRequestDto;
use App\Entity\Client;
use App\Entity\Company;
use App\Entity\Task;
use App\Entity\User;
use App\Enum\TaskStatus;
use App\Service\ClientService;
use App\Service\CompanyService;
use App\Service\TaskService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class TaskServiceTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private TaskService $taskService;
    private CompanyService $companyService;
    private UserService $userService;
    private ClientService $clientService;

    public function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->companyService = $this->createMock(CompanyService::class);
        $this->clientService = $this->createMock(ClientService::class);
        $this->userService = $this->createMock(UserService::class);
        $this->taskService = new TaskService($this->entityManager, $this->companyService, $this->clientService, $this->userService);
    }

    public function testCreateTaskWithNullableFields(): void
    {
        $createTaskRequestDto = new CreateTaskRequestDto('Call to the client', '2025-07-22 19:20:00');

        $this->entityManager->expects($this->once())->method('persist')->with($this->isInstanceOf(Task::class));
        $this->entityManager->expects($this->once())->method('flush');

        $task = $this->taskService->create($createTaskRequestDto);

        $this->assertEquals('Call to the client', $task->getName());
        $this->assertEquals(new \DateTimeImmutable('2025-07-22 19:20:00'), $task->getDueDate());
        $this->assertNull($task->getAssignedTo());
        $this->assertNull($task->getUpdatedAt());
        $this->assertNull($task->getClient());
        $this->assertNull($task->getCompany());
        $this->assertSame(TaskStatus::Pending, $task->getStatus());
    }

    public function testCreateTaskWithCompany(): void
    {
        $createTaskRequestDto = new CreateTaskRequestDto('Call to the client', '2025-07-22 19:20:00', null, null, null, 1);
        $companyMock = $this->createMock(Company::class);

        $this->companyService->expects($this->once())->method('getCompanyById')->with(1)->willReturn($companyMock);
        $this->entityManager->expects($this->once())->method('persist')->with($this->isInstanceOf(Task::class));
        $this->entityManager->expects($this->once())->method('flush');

        $task = $this->taskService->create($createTaskRequestDto);
        $this->assertSame($companyMock, $task->getCompany());
    }

    public function testCreateTaskWithClient(): void
    {
        $createTaskRequestDto = new CreateTaskRequestDto('Call to the client', '2025-07-22 19:20:00', null, null, 1, null);
        $clientMock = $this->createMock(Client::class);

        $this->clientService->expects($this->once())->method('getClientById')->with(1)->willReturn($clientMock);
        $this->entityManager->expects($this->once())->method('persist')->with($this->isInstanceOf(Task::class));
        $this->entityManager->expects($this->once())->method('flush');

        $task = $this->taskService->create($createTaskRequestDto);
        $this->assertSame($clientMock, $task->getClient());
    }

    public function testCreateTaskWithAssignedUser(): void
    {
        $createTaskRequestDto = new CreateTaskRequestDto('Call to the client', '2025-07-22 19:20:00', null, 1, null, null);
        $userMock = $this->createMock(User::class);

        $this->userService->expects($this->once())->method('getUserById')->with(1)->willReturn($userMock);
        $this->entityManager->expects($this->once())->method('persist')->with($this->isInstanceOf(Task::class));
        $this->entityManager->expects($this->once())->method('flush');

        $task = $this->taskService->create($createTaskRequestDto);
        $this->assertSame($userMock, $task->getAssignedTo());
    }

    public function testCreateTaskWithCompanyAndClientAndAssignedUser(): void
    {
        $createTaskRequestDto = new CreateTaskRequestDto('Call to the client', '2025-07-22 19:20:00', null, 1, 1, 1);
        $userMock = $this->createMock(User::class);
        $clientMock = $this->createMock(Client::class);
        $companyMock = $this->createMock(Company::class);

        $this->userService->expects($this->once())->method('getUserById')->with(1)->willReturn($userMock);
        $this->clientService->expects($this->once())->method('getClientById')->with(1)->willReturn($clientMock);
        $this->companyService->expects($this->once())->method('getCompanyById')->with(1)->willReturn($companyMock);
        $this->entityManager->expects($this->once())->method('persist')->with($this->isInstanceOf(Task::class));
        $this->entityManager->expects($this->once())->method('flush');

        $task = $this->taskService->create($createTaskRequestDto);
        $this->assertSame($userMock, $task->getAssignedTo());
        $this->assertSame($clientMock, $task->getClient());
        $this->assertSame($companyMock, $task->getCompany());
    }

    public function testUpdateTaskWithFullData(): void
    {
        $updateData = [
            'name' => 'Task new name',
            'dueDate' => '2025-07-22 19:20:00',
            'description' => 'Task new description',
            'company' => 1,
            'assignedTo' => 1,
            'status' => TaskStatus::Cancelled->value,
            'client' => 1,
        ];

        $companyMock = $this->createMock(Company::class);
        $userMock = $this->createMock(User::class);
        $clientMock = $this->createMock(Client::class);

        $task = new Task();
        $task->setName('Call to the client');
        $task->setDescription('Call to the client from company X');
        $task->setDueDate(new \DateTimeImmutable('2025-01-22 19:20:00'));
        $task->setCompany(null);
        $task->setAssignedTo(null);
        $task->setStatus(TaskStatus::Pending);
        $task->setClient(null);

        $this->userService->expects($this->once())->method('getUserById')->with(1)->willReturn($userMock);
        $this->clientService->expects($this->once())->method('getClientById')->with(1)->willReturn($clientMock);
        $this->companyService->expects($this->once())->method('getCompanyById')->with(1)->willReturn($companyMock);

        $updatedTask = $this->taskService->updateTask($task, $updateData);

        $this->assertSame('Task new name', $updatedTask->getName());
        $this->assertSame('Task new description', $updatedTask->getDescription());
        $this->assertSame(TaskStatus::Cancelled, $updatedTask->getStatus());
        $this->assertEquals(new \DateTimeImmutable('2025-07-22 19:20:00'), $updatedTask->getDueDate());
        $this->assertSame($companyMock, $updatedTask->getCompany());
        $this->assertSame($userMock, $updatedTask->getAssignedTo());
        $this->assertSame($clientMock, $updatedTask->getClient());
    }

    public function testUpdateTaskWithNonExistingCompany(): void
    {
        $updateData = [
            'company' => 123
        ];

        $companyMock = $this->createMock(Company::class);

        $task = new Task();
        $task->setName('Call to the client');
        $task->setDescription('Call to the client from company X');
        $task->setCompany($companyMock);

        $this->companyService->expects($this->once())->method('getCompanyById')->with(123)->willReturn(null);

        $updatedTask = $this->taskService->updateTask($task, $updateData);
        $this->assertNull($updatedTask->getCompany());
    }
}
