<?php
namespace App\Validator\Constraints;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueUserEmailValidator extends ConstraintValidator
{
    public function __construct(private readonly UserRepository $userRepository) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueUserEmail) {
            throw new UnexpectedTypeException($constraint, UniqueUserEmail::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $user = $this->userRepository->findOneBy(['email' => $value]);

        if ($user !== null) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
