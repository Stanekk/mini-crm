<?php

namespace App\Mapper;

use App\Dto\Task\TaskDto;
use App\Entity\Task;

class TaskMapper
{
    private UserMapper $userMapper;
    private ClientMapper $clientMapper;
    private CompanyMapper $companyMapper;

    public function __construct(UserMapper $userMapper, ClientMapper $clientMapper, CompanyMapper $companyMapper)
    {
        $this->userMapper = $userMapper;
        $this->clientMapper = $clientMapper;
        $this->companyMapper = $companyMapper;
    }

    public function toDto(Task $task): TaskDto
    {
        $assignedToUser = $task->getAssignedTo();
        if ($assignedToUser) {
            $assignedToUser = $this->userMapper->toDto($assignedToUser);
        }
        $client = $task->getClient();
        if ($client) {
            $client = $this->clientMapper->toDto($client);
        }
        $company = $task->getCompany();
        if ($company) {
            $company = $this->companyMapper->toShortDto($company);
        }

        return new TaskDto(
            $task->getId(),
            $task->getName(),
            $task->getDescription(),
            $task->getDueDate(),
            $task->getCreatedAt(),
            $task->getUpdatedAt(),
            $assignedToUser,
            $client,
            $company,
            $task->getStatus(),
        );
    }
}
