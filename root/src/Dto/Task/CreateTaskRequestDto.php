<?php

namespace App\Dto\Task;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateTaskRequestDto
{
    #[Assert\NotBlank(message: 'A task name is required')]
    public string $name;

    public ?string $description;
    #[Assert\NotBlank(message: 'Due date is required')]
    #[Assert\DateTime(message: 'A task due date should be in the format YYYY-MM-DD hh:mm:ss')]
    public string $dueDate;
    #[Assert\Type(type: 'integer', message: 'The value {{ value }} is not a valid {{ type }}.')]
    public ?int $assignedTo;

    #[Assert\Type(type: 'integer', message: 'The value {{ value }} is not a valid {{ type }}.')]
    public ?int $client;

    #[Assert\Type(type: 'integer', message: 'The value {{ value }} is not a valid {{ type }}.')]
    public ?int $company;

    public function __construct(string $name, string $dueDate, ?string $description = null, ?int $assignedTo = null, ?int $client = null, ?int $company = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->dueDate = $dueDate;
        $this->assignedTo = $assignedTo;
        $this->client = $client;
        $this->company = $company;
    }
}
