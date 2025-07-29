<?php

namespace App\Service;

use App\Dto\Client\CreateClientRequestDto;
use App\Entity\Client;
use App\Event\ClientCreatedEvent;
use App\Helpers\StringSanitizer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ClientService
{
    private EntityManagerInterface $em;
    private CompanyService $companyService;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EntityManagerInterface $entityManager, CompanyService $companyService, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $entityManager;
        $this->companyService = $companyService;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function create(CreateClientRequestDto $dto): Client
    {
        $company = null;
        if (null !== $dto->company) {
            $company = $this->companyService->getCompanyById($dto->company);

            if (!$company) {
                throw new EntityNotFoundException("Company with id {$dto->company} not found");
            }
        }

        $client = new Client();
        $client->setFirstName($dto->firstName);
        $client->setLastName($dto->lastName);
        $client->setEmail($dto->email);
        $client->setPhone($dto->phone);
        $client->setCompany($company);

        $this->em->persist($client);

        $this->em->flush();

        $clientCreatedEvent = new ClientCreatedEvent($client);
        $this->eventDispatcher->dispatch($clientCreatedEvent);

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
        if (array_key_exists('firstName', $data) && null !== $data['firstName']) {
            $client->setFirstName($data['firstName']);
        }
        if (array_key_exists('lastName', $data) && null !== $data['lastName']) {
            $client->setLastName($data['lastName']);
        }
        if (array_key_exists('phone', $data)) {
            $trimmedPhone = trim($data['phone']);
            if ('' === $trimmedPhone) {
                $client->setPhone(null);
            } else {
                $sanitizedPhone = StringSanitizer::sanitizePhone($trimmedPhone);
                $client->setPhone($sanitizedPhone);
            }
        }
        if (array_key_exists('company', $data)) {
            if (null === $data['company']) {
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
