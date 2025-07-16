<?php

namespace App\Tests\Mapper;

use App\Dto\CompanyDto;
use App\Entity\Company;
use App\Mapper\CompanyMapper;
use PHPUnit\Framework\TestCase;

class CompanyMapperTest extends TestCase
{
    public function testToDto()
    {
        $company = new Company();
        $reflection = new \ReflectionClass(Company::class);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($company, 123);
        $company->setName('Name');
        $company->setEmail('email@email.com');
        $company->setVatNumber('123456789PL');
        $created = new \DateTimeImmutable('now');
        $updated = $created->modify('+5 minutes');
        $company->setCreatedAt($created);
        $company->setUpdatedAt($updated);
        $company->setNipNumber('001123456789');
        $company->setNotes('Notes');
        $company->setIsActive(false);

        $companyMapper = new CompanyMapper();
        $dto = $companyMapper->toDto($company);

        $this->assertInstanceOf(CompanyDto::class, $dto);

        $this->assertEquals(123, $dto->id);
        $this->assertEquals('Name', $dto->name);
        $this->assertEquals('email@email.com', $dto->email);
        $this->assertEquals('123456789PL', $dto->vatNumber);
        $this->assertEquals($created, $dto->createdAt);
        $this->assertEquals($updated, $dto->updatedAt);
        $this->assertEquals('001123456789', $dto->nipNumber);
        $this->assertEquals('Notes', $dto->notes);
        $this->assertFalse($dto->isActive);
    }
}
