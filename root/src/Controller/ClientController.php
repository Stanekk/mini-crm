<?php

namespace App\Controller;

use App\Dto\CreateClientRequestDto;
use App\Entity\Client;
use App\Mapper\ClientMapper;
use App\Repository\ClientRepository;
use App\Service\ClientService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
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


    #[Route('/api/clients', name: 'app_clients', methods: ['GET'])]
    public function index(): JSONResponse
    {
        $clients = $this->clientRepository->findAll();
        $clientsDto = array_map(fn(Client $client) => $this->clientMapper->toDto($client), $clients);

        return new JsonResponse($clientsDto, Response::HTTP_OK);
    }

    #[Route('/api/clients', name: 'app_clients_create', methods: ['POST'])]
    public function create(#[MapRequestPayload(validationGroups: ['Default'])] CreateClientRequestDto $dto): JSONResponse
    {
        $client = $this->clientService->create($dto);
        $clientDto = $this->clientMapper->toDto($client);

        return new JsonResponse($clientDto, Response::HTTP_OK);
    }
    
}