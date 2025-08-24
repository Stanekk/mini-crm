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
        $companyDtoShortDto = null;
        if (null != $client->getCompany()) {
            $companyDtoShortDto = $this->companyMapper->toShortDto($client->getCompany());
        }

        return new ClientDto(
            $client->getId(),
            $client->getFirstName(),
            $client->getLastName(),
            $client->getSource(),
            $companyDtoShortDto,
            $client->getEmail(),
            $client->getPhone(),
        );
    }
}
