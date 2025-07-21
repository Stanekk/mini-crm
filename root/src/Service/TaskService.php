<?php

namespace App\Service;

use App\Dto\Task\CreateTaskRequestDto;
use App\Entity\Task;
use App\Enum\TaskStatus;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{
    private EntityManagerInterface $em;
    private CompanyService $companyService;
    private ClientService $clientService;

    public function __construct(EntityManagerInterface $em, CompanyService $companyService, ClientService $clientService)
    {
        $this->em = $em;
        $this->companyService = $companyService;
        $this->clientService = $clientService;
    }

    public function create(CreateTaskRequestDto $dto): Task
    {
        $company = null;
        $client = null;

        if ($dto->company) {
            $company = $this->companyService->getCompanyById($dto->company);
        }
        if ($dto->client) {
            $client = $this->clientService->getClientById($dto->client);
        }

        $dueDate = new \DateTimeImmutable($dto->dueDate);
        $task = new Task();
        $task->setName($dto->name);
        $task->setDescription($dto->description);
        $task->setDueDate($dueDate);
        $task->setCompany($company);
        $task->setClient($client);
        $this->em->persist($task);
        $this->em->flush();

        return $task;
    }

    public function getTaskById(int $id): ?Task
    {
        $task = $this->em->getRepository(Task::class)->find($id);

        if (!$task) {
            return null;
        }

        return $task;
    }

    public function deleteTask(Task $task): void
    {
        $this->em->remove($task);
        $this->em->flush();
    }

    public function updateTask(Task $task, array $data): Task
    {
        if (array_key_exists('name', $data) && null !== $data['name']) {
            $task->setName($data['name']);
        }
        if (array_key_exists('description', $data)) {
            $task->setDescription($data['description']);
        }
        if (array_key_exists('dueDate', $data) && null !== $data['dueDate']) {
            $dueDate = new \DateTimeImmutable($data['dueDate']);
            $task->setDueDate($dueDate);
        }
        if (array_key_exists('company', $data)) {
            if (null !== $data['company']) {
                $company = $this->companyService->getCompanyById($data['company']);
                $task->setCompany($company);
            } else {
                $task->setCompany(null);
            }
        }
        if (array_key_exists('client', $data)) {
            if (null !== $data['client']) {
                $client = $this->clientService->getClientById($data['client']);
                $task->setClient($client);
            } else {
                $task->setClient(null);
            }
        }
        if (array_key_exists('status', $data) && null !== $data['status']) {
            $task->setStatus(TaskStatus::from($data['status']));
        }
        $task->setUpdatedAt(new \DateTimeImmutable());

        return $task;
    }
}
