<?php

namespace App\Tests\Service;

use App\Dto\Task\CreateTaskRequestDto;
use App\Entity\Task;
use App\Enum\TaskStatus;
use App\Service\ClientService;
use App\Service\CompanyService;
use App\Service\TaskService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class TaskServiceTest extends TestCase
{
    private $entityManager;
    private TaskService $taskService;
    private CompanyService $companyService;
    private UserService $userService;

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
}
