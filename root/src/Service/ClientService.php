<?php

namespace App\Service;

use App\Dto\CreateClientRequestDto;
use App\Dto\UpdateClientRequestDto;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ClientService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function create(CreateClientRequestDto $dto): Client
    {
        $client = new Client();
        $client->setFirstName($dto->firstName);
        $client->setLastName($dto->lastName);
        $client->setEmail($dto->email);
        $client->setPhone($dto->phone);
        $client->setCompany($dto->company);

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
        $accessor = PropertyAccess::createPropertyAccessor();

        foreach ($data as $property => $value) {
            if (property_exists($client, $property) || method_exists($client, 'set' . ucfirst($property))) {
                $accessor->setValue($client, $property, $value);
            }
        }
        $this->em->flush();

        return $client;
    }

}