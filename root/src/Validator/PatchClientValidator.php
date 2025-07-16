<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class PatchClientValidator
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('data', new Assert\Callback(function ($payload, $context) {
            if (array_key_exists('firstName', $payload)) {
                $value = trim((string) $payload['firstName']);
                if ('' === $value) {
                    $context->buildViolation('First name cannot be blank.')
                        ->atPath('firstName')
                        ->addViolation();
                }
            }
        }));

        $metadata->addPropertyConstraint('data', new Assert\Callback(function ($payload, $context) {
            if (array_key_exists('lastName', $payload)) {
                $value = trim((string) $payload['lastName']);
                if ('' === $value) {
                    $context->buildViolation('Last name cannot be blank.')
                        ->atPath('lastName')
                        ->addViolation();
                }
            }
        }));
    }

    public function __get($name)
    {
        return $this->data[$name] ?? null;
    }
}
