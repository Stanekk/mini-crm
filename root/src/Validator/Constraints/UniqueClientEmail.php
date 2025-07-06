<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueClientEmail extends Constraint
{
    public string $message = 'Client email "{{ value }}" is already taken.';
}

