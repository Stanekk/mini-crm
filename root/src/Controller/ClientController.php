<?php

namespace App\Controller;

use App\Dto\CreateClientRequestDto;
use App\Entity\Client;
use App\Mapper\ClientMapper;
use App\Repository\ClientRepository;
use App\Service\ClientService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    private ClientRepository $clientRepository;
    private ClientMapper $clientMapper;
    private ClientService $clientService;

    public function __construct(ClientRepository $clientRepository, ClientMapper $clientMapper, ClientService $clientService)
    {
        $this->clientRepository = $clientRepository;
        $this->clientMapper = $clientMapper;
        $this->clientService = $clientService;
    }

    #[Route('/api/clients', name: 'app_client_list', methods: ['GET'])]
    public function index(): JSONResponse
    {
        $clients = $this->clientRepository->findAll();
        $clientsDto = array_map(fn(Client $client) => $this->clientMapper->toDto($client), $clients);

        return new JsonResponse($clientsDto, Response::HTTP_OK);
    }

    #[Route('/api/clients', name: 'app_client_create', methods: ['POST'])]
    public function create(#[MapRequestPayload(validationGroups: ['Default'])] CreateClientRequestDto $dto): JSONResponse
    {
        $client = $this->clientService->create($dto);
        $clientDto = $this->clientMapper->toDto($client);

        return new JsonResponse($clientDto, Response::HTTP_CREATED);
    }

    #[Route('/api/clients/{id}', name: 'app_client_details', methods: ['GET'])]
    public function details(int $id): JSONResponse
    {
        $client = $this->clientService->getClientById($id);

        if (!$client) {
            throw new NotFoundHttpException('Client not found.');
        }

        $clientDto = $this->clientMapper->toDto($client);

        return new JsonResponse($clientDto, Response::HTTP_OK);
    }

    #[Route('/api/clients/{id}', name: 'app_client_delete', methods: ['DELETE'])]
    public function delete(int $id): JSONResponse
    {
        $client = $this->clientService->getClientById($id);

        if (!$client) {
            throw new NotFoundHttpException('Client not found.');
        }

        $this->clientService->deleteClient($client);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/clients/{id}', name: 'app_client_update', methods: ['PATCH'])]
    public function update(int $id, Request $request): JSONResponse
    {
        $client = $this->clientService->getClientById($id);
        $data = $request->toArray();

        if (!$client) {
            throw new NotFoundHttpException('Client not found.');
        }

        $client = $this->clientService->updateClient($client, $data);
        $updatedClientDto = $this->clientMapper->toDto($client);

        return new JsonResponse($updatedClientDto, Response::HTTP_OK);
    }
    
}