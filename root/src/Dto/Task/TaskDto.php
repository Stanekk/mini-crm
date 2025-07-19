<?php

namespace App\Dto\Task;

use App\Dto\Client\ClientDto;
use App\Dto\Company\CompanyDto;
use App\Dto\User\UserDto;
use App\Enum\TaskStatus;

final readonly class TaskDto
{
    public int $id;
    public string $name;
    public ?string $description;
    public ?\DateTimeImmutable $dueDate;
    public \DateTimeImmutable $createdAt;
    public ?\DateTimeImmutable $updatedAt;
    public ?UserDto $assignedTo;
    public ?ClientDto $client;
    public ?CompanyDto $company;
    public TaskStatus $status;

    public function __construct(int $id, string $name, ?string $description, \DateTimeImmutable $dueDate, \DateTimeImmutable $createdAt, ?\DateTimeImmutable $updatedAt, ?UserDto $assignedTo, ?ClientDto $client, ?CompanyDto $company, TaskStatus $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->dueDate = $dueDate;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->assignedTo = $assignedTo;
        $this->client = $client;
        $this->company = $company;
        $this->status = $status;
    }
}
