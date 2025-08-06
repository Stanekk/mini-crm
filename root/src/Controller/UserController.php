<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\Role;
use App\Mapper\UserMapper;
use App\Repository\UserRepository;
use App\Service\Filter\UserFilterService;
use App\Service\PaginationService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserRepository $userRepository;
    private UserMapper $userMapper;
    private PaginationService $paginationService;
    private UserService $userService;
    private UserFilterService $userFilterService;

    public function __construct(UserRepository $repository, UserMapper $userMapper, PaginationService $paginationService, UserService $userService, UserFilterService $userFilterService)
    {
        $this->userRepository = $repository;
        $this->userMapper = $userMapper;
        $this->paginationService = $paginationService;
        $this->userService = $userService;
        $this->userFilterService = $userFilterService;
    }

    #[Route('/api/users', name: 'app_users_list', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $page = max(1, $request->query->getInt('page', 1));
        $qb = $this->userRepository->createQueryBuilder('user');

        $userFilter = $this->userFilterService->createFilterFromRequest($request);
        $this->userFilterService->applyFilters($qb, $userFilter);

        $result = $this->paginationService->paginate($qb, $page,
            fn (User $user) => $this->userMapper->toDto($user)
        );

        return new JsonResponse($result, Response::HTTP_OK);
    }

    #[Route('/api/users/{id}', name: 'app_user_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $this->denyAccessUnlessGranted(Role::Admin->value);

        $user = $this->userService->getUserById($id);

        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        $this->userService->deleteUser($user);

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/me', name: 'app_me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $currentUser = $this->getUser();
        $userDto = $this->userMapper->toDto($currentUser);

        return new JsonResponse($userDto, Response::HTTP_OK);
    }
}
