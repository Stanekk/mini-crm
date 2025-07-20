<?php

namespace App\Controller;

use App\Dto\Task\CreateTaskRequestDto;
use App\Entity\Task;
use App\Mapper\TaskMapper;
use App\Repository\TaskRepository;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    private TaskService $taskService;
    private TaskMapper $taskMapper;
    private TaskRepository $taskRepository;

    public function __construct(TaskService $taskService, TaskMapper $taskMapper, TaskRepository $taskRepository)
    {
        $this->taskService = $taskService;
        $this->taskMapper = $taskMapper;
        $this->taskRepository = $taskRepository;
    }

    #[Route('/api/tasks', name: 'app_task_create', methods: ['POST'])]
    public function create(#[MapRequestPayload(validationGroups: ['Default'])] CreateTaskRequestDto $dto): JsonResponse
    {
        $task = $this->taskService->create($dto);
        $taskDto = $this->taskMapper->toDto($task);

        return new JsonResponse($taskDto, Response::HTTP_CREATED);
    }

    #[Route('/api/tasks', name: 'app_task_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $tasks = $this->taskRepository->findAll();
        $tasksDto = array_map(fn (Task $task) => $this->taskMapper->toDto($task), $tasks);

        return new JsonResponse($tasksDto, Response::HTTP_OK);
    }

    #[Route('/api/tasks/{id}', name: 'app_task_details', methods: ['GET'])]
    public function details(int $id): JsonResponse
    {
        $task = $this->taskService->getTaskById($id);
        if (!$task) {
            throw new NotFoundHttpException('Task not found.');
        }
        $taskDto = $this->taskMapper->toDto($task);

        return new JsonResponse($taskDto, Response::HTTP_OK);
    }
}
