<?php

namespace App\Service;

use App\Dto\CreateCompanyRequestDto;
use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

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
        $company->setIsActive($dto->isActive);

        $this->em->persist($company);
        $this->em->flush();
        
        return $company;
    }

    public function getCompanyById(int $id): ?Company
    {
        $company = $this->em->getRepository(Company::class)->find($id);
        if (!$company) return null;

        return $company;
    }

    public function deleteCompany(Company $company): void
    {
        $this->em->remove($company);
        $this->em->flush();
    }

    public function updateCompany(Company $company, array $data): Company
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        foreach ($data as $property => $value) {
            if (property_exists($company, $property) || method_exists($company, 'set' . ucfirst($property))) {
                $accessor->setValue($company, $property, $value);
            }
        }
        $this->em->flush();

        return $company;
    }
}