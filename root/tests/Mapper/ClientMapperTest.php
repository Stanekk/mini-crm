<?php

namespace App\Tests\Mapper;

use App\Dto\ClientDto;
use App\Entity\Client;
use App\Mapper\ClientMapper;
use App\Mapper\CompanyMapper;
use PHPUnit\Framework\TestCase;

class ClientMapperTest extends TestCase
{
    private $companyMapper;

    public function setUp(): void
    {
        $this->companyMapper = new CompanyMapper();
    }

    public function testToDtoWithoutCompany()
    {
        $client = new Client();
        $reflection = new \ReflectionClass(Client::class);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($client, 123);

        $client->setFirstName('John');
        $client->setLastName('Doe');
        $client->setEmail('email@example.com');
        $client->setPhone('+48123456789');

        $mapper = new ClientMapper($this->companyMapper);
        $dto = $mapper->toDto($client);

        $this->assertInstanceOf(ClientDto::class, $dto);
        $this->assertEquals(123, $dto->id);
        $this->assertEquals('John', $dto->firstName);
        $this->assertEquals('Doe', $dto->lastName);
        $this->assertEquals('email@example.com', $dto->email);
        $this->assertEquals('+48123456789', $dto->phone);
        $this->assertNull($dto->company);
    }
}
