<?php

namespace App\EventSubscriber;

use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ValidationExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $validationException = $this->extractValidationException($exception);
        if ($validationException instanceof ValidationFailedException) {
            $errors = $this->formatViolations($validationException);
            $event->setResponse(new JsonResponse(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY));
            return;
        }

        if ($exception instanceof EntityNotFoundException) {
            $event->setResponse(new JsonResponse([
                'error' => $exception->getMessage() ?: 'Resource not found'
            ], Response::HTTP_NOT_FOUND));
        }
    }

    private function extractValidationException(\Throwable $exception): ?ValidationFailedException
    {
        if ($exception instanceof ValidationFailedException) {
            return $exception;
        }

        if ($exception instanceof UnprocessableEntityHttpException) {
            $previous = $exception->getPrevious();
            if ($previous instanceof ValidationFailedException) {
                return $previous;
            }
        }

        return null;
    }

    private function formatViolations(ValidationFailedException $exception): array
    {
        $violations = $exception->getViolations();
        $errors = [];

        foreach ($violations as $violation) {
            $propertyPath = preg_replace('/^data\./', '', $violation->getPropertyPath());
            $errors[$propertyPath][] = $violation->getMessage();
        }

        return $errors;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionEvent::class => 'onKernelException',
        ];
    }
}
