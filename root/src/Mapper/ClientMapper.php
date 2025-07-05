<?php

namespace App\Mapper;

use App\Dto\ClientDto;
use App\Entity\Client;

class ClientMapper
{
    public function toDto(Client $client): ClientDto
    {
        return new ClientDto(
            $client->getId(),
            $client->getFirstName(),
            $client->getLastName(),
            $client->getCompany(),
            $client->getEmail(),
            $client->getPhone(),
        );
    }
}