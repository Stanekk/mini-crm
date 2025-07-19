<?php

namespace App\Service;

use App\Dto\CreateTaskRequestDto;
use App\Entity\Task;
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
}
