<?php

namespace App\Controller;

use App\Entity\User;
use App\Mapper\UserMapper;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserRepository $userRepository;
    private UserMapper $userMapper;

    public function __construct(UserRepository $repository, UserMapper $userMapper)
    {
        $this->userRepository = $repository;
        $this->userMapper = $userMapper;
    }

    #[Route('/api/users', name: 'app_users_list', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        $usersDto = array_map(fn (User $user) => $this->userMapper->toDto($user), $users);

        return new JsonResponse($usersDto, Response::HTTP_OK);
    }
}
