<?php

namespace App\Controller;

use App\Entity\User;
use App\Mapper\UserMapper;
use App\Repository\UserRepository;
use App\Service\PaginationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserRepository $userRepository;
    private UserMapper $userMapper;
    private PaginationService $paginationService;

    public function __construct(UserRepository $repository, UserMapper $userMapper, PaginationService $paginationService)
    {
        $this->userRepository = $repository;
        $this->userMapper = $userMapper;
        $this->paginationService = $paginationService;
    }

    #[Route('/api/users', name: 'app_users_list', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $page = max(1, $request->query->getInt('page', 1));
        $qb = $this->userRepository->createQueryBuilder('user');

        $result = $this->paginationService->paginate($qb, $page,
            fn (User $user) => $this->userMapper->toDto($user)
        );

        return new JsonResponse($result, Response::HTTP_OK);
    }
}
