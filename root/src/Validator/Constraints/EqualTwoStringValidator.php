<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class EqualTwoStringValidator extends ConstraintValidator
{
    public function validate($object, Constraint $constraint): void
    {
        if (!$constraint instanceof EqualTwoStrings) {
            throw new UnexpectedTypeException($constraint, EqualTwoStrings::class);
        }

        if (!is_object($object)) {
            return;
        }

        $field1 = $constraint->field;
        $field2 = $constraint->fieldToCompare;

        $value1 = $object->$field1 ?? null;
        $value2 = $object->$field2 ?? null;

        if ($value1 !== $value2) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ field1 }}', $field1)
                ->setParameter('{{ field2 }}', $field2)
                ->atPath($field2)
                ->addViolation();
        }
    }
}

