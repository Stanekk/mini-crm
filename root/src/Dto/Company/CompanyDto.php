<?php

namespace App\Dto\Company;

final readonly class CompanyDto
{
    public int $id;
    public string $name;
    public ?string $vatNumber;
    public ?string $nipNumber;
    public string $email;
    public \DateTimeImmutable $createdAt;
    public \DateTimeImmutable $updatedAt;
    public ?string $notes;
    public bool $isActive;

    public function __construct(
        int $id,
        string $name,
        ?string $vatNumber,
        ?string $nipNumber,
        string $email,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt,
        ?string $notes,
        bool $isActive,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->vatNumber = $vatNumber;
        $this->nipNumber = $nipNumber;
        $this->email = $email;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->notes = $notes;
        $this->isActive = $isActive;
    }
}
