<?php

namespace App\Dto\Filter;

final class UserFilterDto
{
    public ?string $email = null;

    public function __construct($email)
    {
        $this->email = $email;
    }
}
