<?php

namespace App\Controller;

use App\Dto\Task\CreateTaskRequestDto;
use App\Entity\Task;
use App\Mapper\TaskMapper;
use App\Repository\TaskRepository;
use App\Service\TaskService;
use App\Validator\PatchTaskValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskController extends AbstractController
{
    private TaskService $taskService;
    private TaskMapper $taskMapper;
    private TaskRepository $taskRepository;
    private ValidatorInterface $validator;

    public function __construct(TaskService $taskService, TaskMapper $taskMapper, TaskRepository $taskRepository, ValidatorInterface $validator)
    {
        $this->taskService = $taskService;
        $this->taskMapper = $taskMapper;
        $this->taskRepository = $taskRepository;
        $this->validator = $validator;
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

    #[Route('/api/tasks/{id}', name: 'app_task_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $task = $this->taskService->getTaskById($id);
        if (!$task) {
            throw new NotFoundHttpException('Task not found.');
        }
        $this->taskService->deleteTask($task);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/tasks/{id}', name: 'app_task_update', methods: ['PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $task = $this->taskService->getTaskById($id);
        $data = $request->toArray();

        if (!$task) {
            throw new NotFoundHttpException('Task not found.');
        }

        $errors = $this->validator->validate(new PatchTaskValidator($data));

        if (count($errors) > 0) {
            throw new ValidationFailedException(new \stdClass(), $errors);
        }

        $updatedTask = $this->taskService->updateTask($task, $data);
        $updatedTaskDto = $this->taskMapper->toDto($updatedTask);

        return new JsonResponse($updatedTaskDto, Response::HTTP_OK);
    }
}
