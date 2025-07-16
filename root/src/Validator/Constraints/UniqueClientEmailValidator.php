<?php

namespace App\Validator\Constraints;

use App\Repository\ClientRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueClientEmailValidator extends ConstraintValidator
{
    public function __construct(private readonly ClientRepository $clientRepository)
    {
    }

    public function validate($value, Constraint $constraint): void
    {
        if ($this->clientRepository->findOneBy(['email' => $value])) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
