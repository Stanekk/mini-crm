<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueCompanyName extends Constraint
{
    public string $message = 'Company name "{{ value }}" is already taken.';
}
