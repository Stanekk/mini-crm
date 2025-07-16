<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueClientEmail extends Constraint
{
    public string $message = 'Email {{ value }} is already taken.';
}
