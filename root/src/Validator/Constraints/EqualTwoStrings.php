<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS)]
class EqualTwoStrings extends Constraint
{
    public string $field;
    public string $fieldToCompare;
    public string $message = '{{ field1 }} and {{ field2 }} do not match.';

    public function __construct(
        string $field,
        string $fieldToCompare,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct([], $groups, $payload);

        $this->field = $field;
        $this->fieldToCompare = $fieldToCompare;

        if (null !== $message) {
            $this->message = $message;
        }
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return EqualTwoStringValidator::class;
    }
}
