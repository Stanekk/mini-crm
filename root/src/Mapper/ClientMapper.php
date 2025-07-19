<?php

namespace App\Mapper;

use App\Dto\Client\ClientDto;
use App\Entity\Client;

class ClientMapper
{
    private CompanyMapper $companyMapper;

    public function __construct(CompanyMapper $companyMapper)
    {
        $this->companyMapper = $companyMapper;
    }

    public function toDto(Client $client): ClientDto
    {
        $companyDto = null;
        if (null != $client->getCompany()) {
            $companyDto = $this->companyMapper->toDto($client->getCompany());
        }

        return new ClientDto(
            $client->getId(),
            $client->getFirstName(),
            $client->getLastName(),
            $companyDto,
            $client->getEmail(),
            $client->getPhone(),
        );
    }
}
