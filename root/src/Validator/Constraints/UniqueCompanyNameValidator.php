<?php

namespace App\Validator\Constraints;

use App\Repository\CompanyRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueCompanyNameValidator extends ConstraintValidator
{
    public function __construct(private readonly CompanyRepository $companyRepository) {}

    public function validate($value, Constraint $constraint): void
    {
        if ($this->companyRepository->findOneBy(['name' => $value])) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
