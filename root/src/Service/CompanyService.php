<?php

namespace App\Service;

use App\Dto\CreateCompanyRequestDto;
use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;

class CompanyService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create(CreateCompanyRequestDto $dto): Company
    {
        $company = new Company();
        $company->setName($dto->name);
        $company->setEmail($dto->email);
        $company->setVatNumber($dto->vatNumber);
        $company->setNipNumber($dto->nipNumber);
        $company->setNotes($dto->notes);

        $this->em->persist($company);
        $this->em->flush();
        
        return $company;
    }
}