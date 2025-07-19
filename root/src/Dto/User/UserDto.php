<?php

namespace App\Dto\User;

final readonly class UserDto
{
    public int $id;
    public string $email;

    public function __construct(int $id, string $email)
    {
        $this->id = $id;
        $this->email = $email;
    }
}
