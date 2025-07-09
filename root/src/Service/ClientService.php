<?php

namespace App\Service;

use App\Dto\CreateClientRequestDto;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class ClientService
{
    private EntityManagerInterface $em;
    private CompanyService $companyService;

    public function __construct(EntityManagerInterface $entityManager, CompanyService $companyService)
    {
        $this->em = $entityManager;
        $this->companyService = $companyService;
    }

    public function create(CreateClientRequestDto $dto): Client
    {
        $company = null;
        if ($dto->company) {
            $company = $this->companyService->getCompanyById($dto->company);
        }

        if (!$company) {
            throw new EntityNotFoundException("Company with id {$dto->company} not found");
        }

        $client = new Client();
        $client->setFirstName($dto->firstName);
        $client->setLastName($dto->lastName);
        $client->setEmail($dto->email);
        $client->setPhone($dto->phone);
        $client->setCompany($company);

        $this->em->persist($client);

        $this->em->flush();

        return $client;
    }

    public function getClientById(int $id): ?Client
    {
        $client = $this->em->getRepository(Client::class)->find($id);

        if (!$client) {
            return null;
        }

        return $client;
    }

    public function deleteClient(Client $client): void
    {
        $this->em->remove($client);
        $this->em->flush();
    }

    public function updateClient(Client $client, array $data): Client
    {
        if (array_key_exists('firstName', $data) && $data['firstName'] !== null) {
            $client->setFirstName($data['firstName']);
        }
        if (array_key_exists('lastName', $data) && $data['lastName'] !== null) {
            $client->setLastName($data['lastName']);
        }
        if (array_key_exists('phone', $data) && $data['phone']) {
            if (trim($data['phone']) === '') {
                $client->setPhone(null);
            } else {
                $client->setPhone($data['phone']);
            }
        }
        if (array_key_exists('company', $data)) {
            if ($data['company'] === null) {
                $client->setCompany(null);
            } else {
                $company = $this->companyService->getCompanyById($data['company']);
                $client->setCompany($company);
            }
        }
        
        $this->em->flush();

        return $client;
    }

}