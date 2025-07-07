<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ValidationExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof UnprocessableEntityHttpException) {
            $previous = $exception->getPrevious();

            if ($previous instanceof ValidationFailedException) {
                $violations = $previous->getViolations();

                $errors = [];

                foreach ($violations as $violation) {
                    $propertyPath = $violation->getPropertyPath();
                    $message = $violation->getMessage();
                    $errors[$propertyPath][] = $message;
                }

                $response = new JsonResponse(['errors' => $errors], 422);
                $event->setResponse($response);
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionEvent::class => 'onKernelException',
        ];
    }
}
