<?php

namespace App\Controller;

use App\Dto\CreateUserRequestDto;
use App\Service\RegisterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/api/register', name: 'app_register', methods: ['POST'])]
    public function index(#[MapRequestPayload(validationGroups: ['Default'])] CreateUserRequestDto $dto, RegisterService $registerService): JsonResponse
    {
        $user = $registerService->registerUser($dto);
        return new JsonResponse($user, Response::HTTP_OK);
    }

}