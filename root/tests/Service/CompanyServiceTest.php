<?php

namespace App\Tests\Service;

use App\Dto\CreateCompanyRequestDto;
use App\Entity\Company;
use App\Service\CompanyService;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class CompanyServiceTest extends TestCase
{

    private $entityManager;
    private CompanyService $companyService;

    public function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManager::class);
        $this->companyService = new CompanyService($this->entityManager);
    }
    public function testCreateCompany(): void
    {
        $companyDto = new CreateCompanyRequestDto("Company", "email@example.com", "112334", "112345PL", "Notes", true);

        $this->entityManager->expects($this->once())->method('persist')->with($this->isInstanceOf(Company::class));

        $this->entityManager->expects($this->once())->method('flush');

        $company = $this->companyService->create($companyDto);

        $this->assertEquals('Company', $company->getName());
        $this->assertEquals('email@example.com', $company->getEmail());
        $this->assertEquals('112334', $company->getVatNumber());
        $this->assertEquals('112345PL', $company->getNipNumber());
        $this->assertEquals('Notes', $company->getNotes());
        $this->assertTrue($company->isActive());

    }

    public function testCreateCompanyWithNullableFields(): void
    {
        $companyDto = new CreateCompanyRequestDto("Company", "email@example.com", null, null, null, true);

        $this->entityManager->expects($this->once())->method('persist')->with($this->isInstanceOf(Company::class));

        $this->entityManager->expects($this->once())->method('flush');

        $company = $this->companyService->create($companyDto);

        $this->assertEquals('Company', $company->getName());
        $this->assertEquals('email@example.com', $company->getEmail());
        $this->assertNull($company->getVatNumber());
        $this->assertNull($company->getNipNumber());
        $this->assertNull($company->getNotes());
        $this->assertTrue($company->isActive());

    }

    public function testUpdateCompany(): void
    {
        $company = new Company();
        $company->setEmail('company@email.com');
        $company->setIsActive(true);
        $company->setNipNumber('123456789');
        $company->setNotes('Notes');

        $updateCompanyData = [
            'name' => 'New Company Name',
            'isActive' => false,
            'email' => 'new@company.com',
            'notes' => null,
            'nipNumber' => '99888666',
        ];

        $this->entityManager->expects($this->once())->method('flush');

        $updatedCompany = $this->companyService->updateCompany($company, $updateCompanyData);

        $this->assertSame('New Company Name', $updatedCompany->getName());
        $this->assertSame('new@company.com', $updatedCompany->getEmail());
        $this->assertSame('99888666', $updatedCompany->getNipNumber());
        $this->assertFalse($updatedCompany->isActive());
        $this->assertNull($updatedCompany->getNotes());
    }
}