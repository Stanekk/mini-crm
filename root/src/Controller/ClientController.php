<?php

namespace App\Controller;

use App\Entity\Client;
use App\Mapper\ClientMapper;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    #[Route('/api/clients', name: 'app_clients')]
    public function index(ClientRepository $clientRepository, ClientMapper $clientMapper): JSONResponse
    {
        $clients = $clientRepository->findAll();
        $clientsDto = array_map(fn(Client $client) => $clientMapper->toDto($client), $clients);

        return new JsonResponse($clientsDto, Response::HTTP_OK);
    }
    
}