<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueUserEmail extends Constraint
{
    public string $message = 'Email {{ value }} is already taken.';
}
