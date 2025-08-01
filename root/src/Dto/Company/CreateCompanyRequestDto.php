<?php

namespace App\Dto\Company;

use App\Validator\Constraints as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateCompanyRequestDto
{
    #[Assert\NotBlank(message: 'A company name should not be blank')]
    #[CustomAssert\UniqueCompanyName]
    public ?string $name;

    #[Assert\NotNull(message: 'Company email address is required')]
    #[Assert\Email(message: 'Please enter a valid email address')]
    public ?string $email;

    public ?string $vatNumber;
    public ?string $nipNumber;
    public ?string $notes;
    public ?bool $isActive;

    public function __construct(
        ?string $name = null,
        ?string $email = null,
        ?string $vatNumber = null,
        ?string $nipNumber = null,
        ?string $notes = null,
        ?bool $isActive = true,
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->vatNumber = $vatNumber;
        $this->nipNumber = $nipNumber;
        $this->notes = $notes;
        $this->isActive = $isActive;
    }
}
