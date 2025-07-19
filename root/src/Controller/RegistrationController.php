<?php

namespace App\Controller;

use App\Dto\User\CreateUserRequestDto;
use App\Mapper\UserMapper;
use App\Service\RegisterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    private RegisterService $registerService;
    private UserMapper $userMapper;

    public function __construct(RegisterService $registerService, UserMapper $userMapper)
    {
        $this->registerService = $registerService;
        $this->userMapper = $userMapper;
    }

    #[Route('/api/register', name: 'app_register', methods: ['POST'])]
    public function index(#[MapRequestPayload(validationGroups: ['Default'])] CreateUserRequestDto $dto): JsonResponse
    {
        $user = $this->registerService->registerUser($dto);
        $userDto = $this->userMapper->toDto($user);

        return new JsonResponse($userDto, Response::HTTP_CREATED);
    }
}
