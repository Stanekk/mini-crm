<?php

namespace App\Validator;

use App\Validator\Constraints\UniqueCompanyName;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PatchCompanyValidator
{
    private array $data;
    private ValidatorInterface $validator;

    public function __construct(
        array $data,
        ValidatorInterface $validator,
    ) {
        $this->data = $data;
        $this->validator = $validator;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('data', new Assert\Callback(function ($payload, $context) {
            if (array_key_exists('email', $payload)) {
                $value = trim((string) $payload['email']);

                if ('' === $value) {
                    $context->buildViolation('Email cannot be blank.')
                        ->atPath('email')
                        ->addViolation();
                }
            }
        }));

        $metadata->addPropertyConstraint('data', new Assert\Callback(function ($payload, $context) {
            if (array_key_exists('name', $payload)) {
                $value = trim((string) $payload['name']);

                if ('' === $value) {
                    $context->buildViolation('A company name cannot be blank.')
                        ->atPath('email')
                        ->addViolation();
                }

                /** @var ValidatorInterface $validator */
                $validator = $context->getValidator();

                $violations = $validator->validate($value, [
                    new UniqueCompanyName(),
                ]);

                foreach ($violations as $violation) {
                    $context->buildViolation($violation->getMessage())
                        ->atPath('name')
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
