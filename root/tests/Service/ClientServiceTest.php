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


}
