<?php

namespace App\Dto\Task;

use App\Dto\Client\ClientDto;
use App\Dto\Company\CompanyShortDto;
use App\Dto\User\UserDto;
use App\Enum\DataSource;
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
    public ?CompanyShortDto $company;
    public TaskStatus $status;
    public DataSource $source;

    public function __construct(int $id, string $name, ?string $description, \DateTimeImmutable $dueDate, \DateTimeImmutable $createdAt, ?\DateTimeImmutable $updatedAt, ?UserDto $assignedTo, ?ClientDto $client, ?CompanyShortDto $company, TaskStatus $status, DataSource $source)
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
        $this->source = $source;
    }
}
