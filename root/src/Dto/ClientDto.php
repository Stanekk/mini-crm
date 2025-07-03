<?php

namespace App\Dto;

class ClientDto
{
    public int $id;
    public string $firstName;
    public string $lastName;

    public function __construct(int $id, string $firstName, string $lastName)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
}
