<?php

namespace App\Validator;

use App\Enum\TaskStatus;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class PatchTaskValidator
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('data', new Assert\Callback(function ($payload, $context) {
            if (array_key_exists('name', $payload)) {
                $value = trim((string) $payload['name']);
                if ('' === $value) {
                    $context->buildViolation('Name cannot be blank.')
                        ->atPath('name')
                        ->addViolation();
                }
            }
        }));

        $metadata->addPropertyConstraint('data', new Assert\Callback(function ($payload, $context) {
            if (array_key_exists('dueDate', $payload)) {
                try {
                    $dueDate = new \DateTimeImmutable($payload['dueDate']);
                } catch (\Exception $e) {
                    $context->buildViolation('Due date is not valid.')
                        ->atPath('dueDate')
                        ->addViolation();
                }
            }
        }));

        $metadata->addPropertyConstraint('data', new Assert\Callback(function ($payload, $context) {
            if (array_key_exists('status', $payload)) {
                if (!in_array($payload['status'], TaskStatus::VALUES)) {
                    $context->buildViolation('Invalid task status.')
                        ->atPath('status')
                        ->addViolation();
                }
            }
        }));

        $metadata->addPropertyConstraint('data', new Assert\Callback(function ($payload, $context) {
            if (array_key_exists('company', $payload)) {
                if (is_string($payload['company'])) {
                    $context->buildViolation('Invalid company id.')
                        ->atPath('company')
                        ->addViolation();
                }
            }
        }));

        $metadata->addPropertyConstraint('data', new Assert\Callback(function ($payload, $context) {
            if (array_key_exists('client', $payload)) {
                if (is_string($payload['client'])) {
                    $context->buildViolation('Invalid client id.')
                        ->atPath('client')
                        ->addViolation();
                }
            }
        }));

        $metadata->addPropertyConstraint('data', new Assert\Callback(function ($payload, $context) {
            if (array_key_exists('assignedTo', $payload)) {
                if (is_string($payload['assignedTo'])) {
                    $context->buildViolation('Invalid assigned to id.')
                        ->atPath('assignedTo')
                        ->addViolation();
                }
            }
        }));
    }
}
