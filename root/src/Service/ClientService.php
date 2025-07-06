<?php

namespace App\Service;

use App\Dto\CreateClientRequestDto;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;

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

}