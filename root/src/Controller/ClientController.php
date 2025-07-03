<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    #[Route('/api/clients', name: 'app_clients')]
    public function index(ClientRepository $clientRepository): JSONResponse
    {
        $clients = $clientRepository->findAll();
        $clientsDto = array_map(fn($client) => $client->toDto(), $clients);

        return new JsonResponse($clientsDto, Response::HTTP_OK);
    }
    
}