<?php

namespace App\Dto;

final readonly class ClientDto
{
    public int $id;
    public string $firstName;
    public string $lastName;
    public ?CompanyDto $company;
    public string $email;
    public ?string $phone;

    public function __construct(int $id, string $firstName, string $lastName, ?CompanyDto $company, string $email, ?string $phone)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->company = $company;
        $this->email = $email;
        $this->phone = $phone;
    }
}
