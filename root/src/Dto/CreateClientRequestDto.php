<?php

namespace App\Dto;

use App\Validator\Constraints as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateClientRequestDto
{
    #[Assert\NotBlank(message: 'A client first name should not be blank')]
    public ?string $firstName;
    #[Assert\NotBlank(message: 'A client last name should not be blank')]
    public ?string $lastName;

    #[Assert\Email(message: 'Please enter a valid email address')]
    #[Assert\NotNull(message: 'A client email should not be null')]
    #[CustomAssert\UniqueClientEmail]
    public ?string $email;
    public ?string $phone;
    public ?int $company;

    public function __construct(?string $firstName = null, ?string $lastName = null, ?string $email = null, ?string $phone = null, ?int $company = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phone = $phone;
        $this->company = $company;
    }
}
