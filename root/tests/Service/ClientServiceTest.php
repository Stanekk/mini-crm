<?php

namespace App\Tests\Service;

use App\Dto\CreateClientRequestDto;
use App\Entity\Client;
use App\Entity\Company;
use App\Service\ClientService;
use App\Service\CompanyService;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class ClientServiceTest extends TestCase
{
    private $entityManager;
    private $companyService;
    private ClientService $clientService;

    public function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManager::class);
        $this->companyService = $this->createMock(CompanyService::class);
        $this->clientService = new ClientService($this->entityManager, $this->companyService);
    }

    public function testCreateClientWithoutCompany(): void
    {
        $dto = new CreateClientRequestDto('firstName', 'lastName', 'email@email.com', '+48555111222');

        $this->entityManager->expects($this->once())->method('persist')->with($this->isInstanceOf(Client::class));
        $this->entityManager->expects($this->once())->method('flush');

        $client = $this->clientService->create($dto);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertSame('firstName', $client->getFirstName());
        $this->assertSame('lastName', $client->getLastName());
        $this->assertSame('email@email.com', $client->getEmail());
        $this->assertSame('+48555111222', $client->getPhone());
        $this->assertNull($client->getCompany());
    }

    public function testCreateClientWithCompany(): void
    {
        $dto = new CreateClientRequestDto('firstName', 'lastName', 'email@email.com', '+48555111222', 123);

        $company = new Company();
        $company->setName('Extra company');
        $this->companyService->method('getCompanyById')->with(123)->willReturn($company);

        $this->entityManager->expects($this->once())->method('persist')->with($this->isInstanceOf(Client::class));
        $this->entityManager->expects($this->once())->method('flush');

        $client = $this->clientService->create($dto);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertSame('firstName', $client->getFirstName());
        $this->assertSame('lastName', $client->getLastName());
        $this->assertSame('email@email.com', $client->getEmail());
        $this->assertSame('+48555111222', $client->getPhone());
        $this->assertSame($company, $client->getCompany());
    }

    public function testUpdateClientWithCompanyNull(): void
    {
        $company = new Company();
        $company->setName('Extra company');
        $company->setVatNumber("23423432434");

        $client = new Client();
        $client->setFirstName('Thomas');
        $client->setLastName('Muller');
        $client->setEmail('thomas@example.com');
        $client->setPhone('+4899112233');
        $client->setCompany($company);

        $this->assertInstanceOf(Company::class, $client->getCompany());

        $data = [
            'firstName' => 'firstName',
            'lastName' => 'lastName',
            'phone' => '+48555111222',
            'company' => null,
        ];

        $client = $this->clientService->updateClient($client, $data);
        $this->assertInstanceOf(Client::class, $client);
        $this->assertSame($data['firstName'], $client->getFirstName());
        $this->assertSame($data['lastName'], $client->getLastName());
        $this->assertSame($data['phone'], $client->getPhone());
        $this->assertNull($client->getCompany());

    }

    public function testUpdateClientWithCompany(): void
    {
        $company = new Company();
        $company->setName('Extra company');
        $company->setVatNumber("23423432434");

        $client = new Client();
        $client->setFirstName('Thomas');
        $client->setLastName('Muller');
        $client->setEmail('thomas@example.com');
        $client->setPhone('+4899112233');
        $client->setCompany($company);

        $this->assertInstanceOf(Company::class, $client->getCompany());

        $data = [
            'firstName' => 'firstName',
            'lastName' => 'lastName',
            'phone' => '+48555111222',
            'company' => 123,
        ];

        $company = new Company();
        $company->setName('Extra company');
        $this->companyService->method('getCompanyById')->with(123)->willReturn($company);

        $client = $this->clientService->updateClient($client, $data);
        $this->assertInstanceOf(Client::class, $client);
        $this->assertSame($data['firstName'], $client->getFirstName());
        $this->assertSame($data['lastName'], $client->getLastName());
        $this->assertSame($data['phone'], $client->getPhone());
        $this->assertSame($company, $client->getCompany());

    }

    public function testUpdateClientWithNonExistingCompany(): void
    {
        $existingCompany = new Company();
        $existingCompany->setName('Old Company');

        $client = new Client();
        $client->setCompany($existingCompany);

        $this->assertInstanceOf(Company::class, $client->getCompany());

        $data = [
            'company' => 999,
        ];

        $this->companyService->method('getCompanyById')->with(999)->willReturn(null);

        $client = $this->clientService->updateClient($client, $data);
        $this->assertNull($client->getCompany());
    }

}
