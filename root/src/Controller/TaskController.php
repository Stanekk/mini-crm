<?php

namespace App\Controller;

use App\Dto\Task\CreateTaskRequestDto;
use App\Mapper\TaskMapper;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    private TaskService $taskService;
    private TaskMapper $taskMapper;

    public function __construct(TaskService $taskService, TaskMapper $taskMapper)
    {
        $this->taskService = $taskService;
        $this->taskMapper = $taskMapper;
    }

    #[Route('/api/tasks', name: 'app_task_create', methods: ['POST'])]
    public function create(#[MapRequestPayload(validationGroups: ['Default'])] CreateTaskRequestDto $dto): JsonResponse
    {
        $task = $this->taskService->create($dto);
        $taskDto = $this->taskMapper->toDto($task);

        return new JsonResponse($taskDto, Response::HTTP_CREATED);
    }
}
