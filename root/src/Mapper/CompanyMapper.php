<?php

namespace App\Mapper;

use App\Dto\Company\CompanyDto;
use App\Entity\Company;

class CompanyMapper
{
    public function toDto(Company $company): CompanyDto
    {
        return new CompanyDto(
            $company->getId(),
            $company->getName(),
            $company->getVatNumber(),
            $company->getNipNumber(),
            $company->getEmail(),
            $company->getCreatedAt(),
            $company->getUpdatedAt(),
            $company->getNotes(),
            $company->isActive()
        );
    }
}
